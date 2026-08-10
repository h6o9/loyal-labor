<?php

namespace App\Traits;

use App\Enums\MessageType;
use App\Enums\RedirectMessage;
use App\Enums\RedirectType;
use Illuminate\Http\RedirectResponse;

trait RedirectHelperTrait
{
    /**
     * @return mixed
     */
    private function returnWithMessageAfterCreate($model, $successMessage, $failedMessage, $routeName): RedirectResponse
    {
        $actionStatus = $model->wasRecentlyCreated;
        [$messageType, $message] = $this->getMessages($successMessage, $failedMessage, $actionStatus);

        return $actionStatus
        ? redirect()->route($routeName)->with([
            'alert-type' => $messageType,
            'message' => $message,
        ])
        : redirect()->back()->with([
            'alert-type' => $messageType,
            'message' => $message,
        ]);
    }

    /**
     * @return mixed
     */
    private function returnWithMessageAfterUpdate($model, $successMessage, $failedMessage, $routeName): RedirectResponse
    {
        $actionStatus = $model->wasChanged();
        [$messageType, $message] = $this->getMessages($successMessage, $failedMessage, $actionStatus);

        return $actionStatus
        ? redirect()->route($routeName)->with([
            'alert-type' => $messageType,
            'message' => $message,
        ])
        : redirect()->back()->with([
            'alert-type' => $messageType,
            'message' => $message,
        ]);
    }

    private function generateMessages($successMessage, $failedMessage): array
    {
        return [
            $successMessage,
            $failedMessage,
        ];
    }

    private function getMessages($successMessage, $failedMessage, $actionStatus): array
    {
        [$successMessage, $failedMessage] = $this->generateMessages($successMessage, $failedMessage);

        $messageType = $actionStatus ? MessageType::SUCCESS->value : MessageType::ERROR->value;
        $message = $actionStatus ? __($successMessage) : __($failedMessage);

        return [
            $messageType,
            $message,
        ];
    }

    private function redirectWithMessage(string $type, ?string $route = null, array $params = [], array $notification = []): RedirectResponse
    {
        $messages = RedirectMessage::getAll();

        if (! $notification) {
            $notification = [
                'message' => __($messages[$type]),
                'alert-type' => ($type === RedirectType::ERROR->value) ? MessageType::ERROR->value : MessageType::SUCCESS->value,
            ];
        }

        if ($route) {
            return redirect()->route($route, $params)->with($notification);
        }

        return redirect()->back()->with($notification);
    }
}
