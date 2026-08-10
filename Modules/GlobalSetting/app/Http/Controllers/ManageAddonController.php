<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomAddon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\GlobalSetting\app\Traits\ArchiveHelperTrait;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use ZipArchive;

class ManageAddonController extends Controller
{
    use ArchiveHelperTrait;

    public function index()
    {
        $addons = CustomAddon::latest()->get();

        return view('globalsetting::addons.manage_addon', ['addons' => $addons]);
    }

    public function installAddon()
    {
        $files = glob(public_path('addons_files') . '/*');

        $addonFiles = [];
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip' && $this->isFirstDirAddons($file)) {
                $fileName                                     = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension                                = pathinfo($file, PATHINFO_EXTENSION);
                $addonFiles[$fileName . '.' . $fileExtension] = $this->checkAndReadJsonFile($file);
            }
        }

        return view('globalsetting::addons.install_addon', ['addonFiles' => $addonFiles]);
    }

    /**
     * @param Request $requestl
     */
    public function installStore(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        $zipFilePath = public_path('addons_files/addon.zip');

        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
        }

        // Store the uploaded file
        $zipFile     = $request->file('zip_file');
        $zipFilePath = $zipFile->move(public_path('addons_files'), 'addon.zip');

        if (!$this->isFirstDirAddons($zipFilePath) && !$this->checkAndReadJsonFile($zipFilePath)) {
            File::delete($zipFilePath);
            $notification = __('Invalid Addon File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $notification = __('Uploaded Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    public function installProcessStart()
    {
        Cache::forget('dynamic_translatable_models');

        $zipFilePath = public_path('addons_files/addon.zip');

        if (!File::exists($zipFilePath)) {
            $notification = __('No Addon File Found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        if (!$this->isFirstDirAddons($zipFilePath)) {
            $notification = __('Invalid Addon File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $file = $zipFilePath;
        if (pathinfo($file, PATHINFO_EXTENSION) === 'zip' && $this->isFirstDirAddons($file)) {
            $addonFile     = $this->checkAndReadJsonFile($file);
            $addonFileJson = json_decode(json_encode($addonFile), true);

            $addonExist = CustomAddon::where('name', $addonFile->name)->first();

            if ($addonExist && count($addonFileJson) > 0 && ($addonFile?->version == $addonExist?->version)) {
                $notification = __('Addon Already Installed');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }

            try {
                $zip = new ZipArchive;
                if ($zip->open($zipFilePath) === true) {
                    $zip->extractTo(base_path());
                    $zip->close();
                } else {
                    $notification = __('Corrupted Zip File');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];

                    return redirect()->back()->with($notification);
                }
            } catch (Exception $e) {
                Log::error($e->getMessage());
                $notification = __('Corrupted Zip File');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }

            $moduleSlug = null;

            try {
                $getModuleJson = $this->checkAndReadJsonFile($file, 'module.json');

                $moduleSlug = $getModuleJson->name;

                DB::beginTransaction();

                $customAddon       = new CustomAddon;
                $customAddon->slug = $getModuleJson->name;
                foreach ($addonFileJson as $key => $value) {
                    $customAddon->$key = is_array($value) ? json_encode($value) : $value;
                }
                $customAddon->status = 0;
                $customAddon->save();

                Module::register($customAddon->slug);

                $wsusJson = $this->checkAndReadJsonFile($file);

                $this->insertRoleAndPermissions($moduleSlug, $wsusJson);

                $this->moveAssetFiles($wsusJson, $moduleSlug);

                DB::commit();

                unlink($zipFilePath);

                $notification = __('Installed Successfully!');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
            } catch (Exception $e) {
                DB::rollBack();
                Module::find($moduleSlug)?->delete();
                logError("Addon Install Error", $e);

                $notification = __('Something went wrong!');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
            }
        }

        return to_route('admin.addons.view')->with($notification);
    }

    /**
     * @param $permissions
     */
    private function insertRoleAndPermissions($slug, $wsusJson)
    {
        try {
            if (Module::find($slug) && isset($wsusJson->options->role_permission)) {
                $rolePermission = $wsusJson->options->role_permission;

                // Ensure permissions exist and are an array
                if (isset($rolePermission->permissions) && is_array($rolePermission->permissions) && count($rolePermission->permissions) > 0) {
                    $permissions     = $rolePermission->permissions;
                    $permissionGroup = $rolePermission->group_name ?? 'default';

                    // Loop through each permission and insert/update in database
                    foreach ($permissions as $permissionName) {
                        $permission = Permission::updateOrCreate([
                            'name'       => $permissionName,
                            'group_name' => $permissionGroup,
                            'guard_name' => 'admin',
                        ]);

                        // Assign the permission to the "Super Admin" role
                        Role::where(['name' => 'Super Admin', 'guard_name' => 'admin'])
                            ->first()?->givePermissionTo($permission);
                    }
                }
            }
        } catch (Exception $e) {
            logError("Insert Role And Permissions Error", $e);
        }
    }

    /**
     * @param $wsusJson
     * @param $moduleName
     */
    public function moveAssetFiles($wsusJson, $moduleName)
    {
        if (isset($wsusJson->options->assets)) {
            $assets = $wsusJson->options->assets;

            // Process CSS assets
            if (isset($assets->css) && is_array($assets->css)) {
                foreach ($assets->css as $cssFile) {
                    $this->moveFileToPublicPath($cssFile, $moduleName);
                }
            }

            // Process JS assets
            if (isset($assets->js) && is_array($assets->js)) {
                foreach ($assets->js as $jsFile) {
                    $this->moveFileToPublicPath($jsFile, $moduleName);
                }
            }
        }
    }

    /**
     * @param $fileData
     * @param $moduleName
     */
    private function moveFileToPublicPath($fileData, $moduleName)
    {
        $sourcePath      = base_path('Modules' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $fileData->path), DIRECTORY_SEPARATOR));
        $destinationPath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $fileData->pasteTo));

        // Ensure the destination directory exists or create it
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Check if the source file exists before moving
        if (File::exists($sourcePath)) {
            $destinationFullPath = $destinationPath . DIRECTORY_SEPARATOR . basename($sourcePath);

            // Copy the file and log the operation
            File::copy($sourcePath, $destinationFullPath);
            logger()->info("Moved {$sourcePath} to {$destinationFullPath}");
        } else {
            logger()->error("Source file not found: {$sourcePath}");
        }
    }

    /**
     * @param $slug
     */
    public function updateStatus($slug)
    {
        $addon = CustomAddon::whereSlug($slug)->firstOrFail();

        $status = $addon->status == 1 ? 0 : 1;

        Module::scan();

        if (!Module::has($addon->slug)) {
            $notification = __('Addon Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return back()->with($notification);
        }

        if ($status) {
            Module::enable($addon->slug);
            $module = Module::find($addon->slug);
            if ($module->isEnabled()) {
                $addon->status = $status;
                // write code to inject the code into the sidebarfile
                $sidebarFilePath    = base_path('resources/views/admin/addons.blade.php');
                $sidebarFileContent = File::get($sidebarFilePath);
                $injectedCode       = "\n@includeIf('" . $module->getLowerName() . "::sidebar')";
                if (strpos($sidebarFileContent, $injectedCode) === false) {
                    // Add the injected code
                    $updatedSidebarFileContent = str_replace('<!-- Addon:Sidebar -->', '<!-- Addon:Sidebar -->' . $injectedCode, $sidebarFileContent);

                    // Write the updated content to the file
                    File::put($sidebarFilePath, $updatedSidebarFileContent);
                }

                // write code to migrate the module
                $name = $module->getName();

                if (!$this->moduleMigration($name)) {
                    $module->disable();
                }
            }
        } else {
            $module = Module::find($addon->slug);
            $module->disable();

            if ($module->isDisabled()) {
                $addon->status = $status;
            }
        }

        $addon->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    /**
     * @param $module
     */
    public function moduleMigration($module)
    {
        try {
            Artisan::call('module:migrate', [
                'module'  => $module,
                '--force' => true,
            ]);

            $seederClass = "Modules\\$module\\Database\\Seeders\\{$module}DatabaseSeeder";

            // Check if the seeder class exists
            if (class_exists($seederClass)) {
                Artisan::call('db:seed', [
                    '--class' => $seederClass,
                    '--force' => true,
                ]);
            } else {
                Log::warning("Seeder class not found: $seederClass");
            }

            return true;
        } catch (\Exception $e) {
            Log::info($e);

            return false;
        }
    }

    public function ModuleRefresh()
    {
        try {

            exec('php composer.phar dump-autoload');

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');

        } catch (Exception $e) {
            logError("Module Refresh Error", $e);
        }
    }

    /**
     * @param $slug
     */
    public function uninstallAddon($slug)
    {
        $addon = CustomAddon::whereSlug($slug)->firstOrFail();

        Module::scan();
        $module = Module::find($addon->slug);

        if (!Module::has($addon->slug)) {
            $notification = __('Addon Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return back()->with($notification);
        }

        if ($module->delete()) {
            $addon->delete();
            // write code to remove the code from the sidebar file
            $sidebarFilePath    = base_path('resources/views/admin/addons.blade.php');
            $sidebarFileContent = File::get($sidebarFilePath);
            $injectedCode       = "\n@includeIf('" . $module->getLowerName() . "::sidebar')";
            if (strpos($sidebarFileContent, $injectedCode)) {
                $updatedSidebarFileContent = str_replace($injectedCode, '', $sidebarFileContent);
                File::put($sidebarFilePath, $updatedSidebarFileContent);
            }
        }

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    public function deleteAddon()
    {
        $this->deleteFolderAndFiles(public_path('addons_files'));

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }
}
