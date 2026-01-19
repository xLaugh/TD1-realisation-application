<?php

namespace App\Console;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\Schema;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Populate database...');

        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->app->getContainer()->get('db');

        // Clear existing data
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("TRUNCATE `employees`");
        $db->getConnection()->statement("TRUNCATE `offices`");
        $db->getConnection()->statement("TRUNCATE `companies`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");

        // Initialize Faker with French locale
        $faker = \Faker\Factory::create('fr_FR');

        // Job titles pool
        $jobTitles = [
            'Développeur', 'Ingénieur', 'Chef de projet', 'Architecte logiciel',
            'DevOps', 'Data Scientist', 'Product Owner', 'Scrum Master',
            'Designer UX/UI', 'Testeur QA', 'Administrateur système', 'DBA',
            'Analyste', 'Consultant', 'Directeur technique', 'Responsable RH'
        ];

        $companies = [];
        $allOffices = [];

        // Generate exactly 3 companies
        $numCompanies = 3;
        $output->writeln("Generating {$numCompanies} companies...");

        for ($i = 0; $i < $numCompanies; $i++) {
            $company = new Company();
            $company->name = $faker->company();
            $company->phone = $faker->phoneNumber();
            $company->email = $faker->companyEmail();
            $company->website = $faker->url();
            $company->image = $faker->imageUrl(800, 600, 'business', true);
            $company->save();

            $companies[] = $company;
            $output->writeln("  - Created company: {$company->name}");

            // Generate 2-3 offices for this company
            $numOffices = $faker->numberBetween(2, 3);
            $companyOffices = [];

            for ($j = 0; $j < $numOffices; $j++) {
                $office = new Office();
                $office->name = $j === 0 ? 'Siège social' : $faker->randomElement(['Bureau de ', 'Agence de ', 'Site de ']) . $faker->city();
                $office->address = $faker->streetAddress();
                $office->city = $faker->city();
                $office->zip_code = $faker->postcode();
                $office->country = 'France';
                $office->email = $faker->optional(0.7)->email();
                $office->phone = $faker->optional(0.6)->phoneNumber();
                $office->company_id = $company->id;
                $office->save();

                $companyOffices[] = $office;
                $allOffices[] = $office;
                $output->writeln("    - Created office: {$office->name} in {$office->city}");
            }

            // Set the first office as head office
            $company->head_office_id = $companyOffices[0]->id;
            $company->save();
        }

        // Generate approximately 10 employees distributed across all offices
        $numEmployees = $faker->numberBetween(30, 33);
        $output->writeln("Generating {$numEmployees} employees...");

        for ($i = 0; $i < $numEmployees; $i++) {
            $employee = new Employee();
            $employee->first_name = $faker->firstName();
            $employee->last_name = $faker->lastName();
            
            // Generate email based on actual employee name
            $emailDomains = ['entreprise.fr', 'company.com', 'societe.fr', 'business.net'];
            $emailPrefix = strtolower($employee->first_name . '.' . $employee->last_name);
            // Remove accents and special characters from email
            $emailPrefix = iconv('UTF-8', 'ASCII//TRANSLIT', $emailPrefix);
            $emailPrefix = preg_replace('/[^a-z0-9.]/', '', $emailPrefix);
            $employee->email = $emailPrefix . '@' . $faker->randomElement($emailDomains);
            $employee->phone = $faker->optional(0.5)->phoneNumber();
            $employee->job_title = $faker->randomElement($jobTitles);
            $employee->office_id = $faker->randomElement($allOffices)->id;
            $employee->save();

            $output->writeln("  - Created employee: {$employee->first_name} {$employee->last_name} ({$employee->job_title})");
        }

        $output->writeln('Database created successfully!');
        return 0;
    }
}
