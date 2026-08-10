<?php

namespace Modules\KnowYourClient\database\seeders;

use Illuminate\Database\Seeder;
use Modules\KnowYourClient\app\Models\KycType;

class KnowYourClientDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kycTypes = [
            'Passport'       => 'A government-issued document that certifies a person\'s identity and citizenship, allowing them to travel internationally.',
            'National ID'    => 'A government-issued identification card that verifies a person\'s identity within their country.',
            'Driver License' => 'A government-issued document that authorizes an individual to operate a motor vehicle, serving as a form of identification.',
            'Utility Bill'   => 'A document issued by a utility company that provides proof of residence, typically showing the customer\'s name and address.',
            'Bank Statement' => 'A summary of financial transactions in a bank account over a specified period, providing proof of income and residence.',
            'Tax Document'   => 'A document related to an individual\'s tax obligations, such as a tax return or tax assessment, used to verify income and financial status.',
        ];

        foreach ($kycTypes as $name => $description) {
            KycType::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }
}
