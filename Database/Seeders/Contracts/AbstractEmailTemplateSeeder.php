<?php

namespace Modules\Email\Database\Seeders\Contracts;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Modules\Email\Models\MailTemplates;
use Modules\Email\Mail\Standard as StandardMailable;
use RuntimeException;

abstract class AbstractEmailTemplateSeeder extends Seeder
{
    /**
     * Skal defineres i concrete seeder
     * Kan nu være en liste af template-navne eller assoc array ['template_name' => ContractClass::class]
     * @var array
     */
    protected array $templates = [];

    /**
     * Modul namespace fx 'Email' hentet fra config
     * @var string
     */
    protected string $moduleName;

    protected array $locales;

    public function __construct()
    {
        $this->locales = config('app.locales', ['da', 'en']);

        if (empty($this->moduleName)) {
            $this->moduleName = $this->getModuleNameFromConfig();
        }
    }

    public function run()
    {
        if (empty($this->moduleName)) {
            throw new RuntimeException('moduleName could not be determined from config.');
        }

        if (empty($this->templates)) {
            return;
        }

        $translationNamespace = strtolower($this->moduleName) . '::emails';

        $this->seedTemplates($this->templates, $translationNamespace);
    }

    protected function getModuleNameFromConfig(): string
    {
        $reflection = new \ReflectionClass(static::class);
        $modulePath = dirname(dirname(dirname($reflection->getFileName()))); // Seeders -> Database -> Module
        $configPath = $modulePath . '/Config/config.php';

        if (!file_exists($configPath)) {
            throw new RuntimeException("Config file not found at {$configPath}");
        }

        $config = require $configPath;

        if (empty($config['name'])) {
            throw new RuntimeException("Module name not set in {$configPath}");
        }

        return $config['name'];
    }

    protected function seedTemplates(array $templateNames, string $translationNamespace): void
    {
        foreach ($templateNames as $key => $value) {
            // Hvis arrayet er simpel liste (ikke assoc), sæt name = value og contract = null
            if (is_int($key)) {
                $name = $value;
                $contract = null;
            } else {
                $name = $key;
                $contract = $value;
            }

            $subject = [];
            $htmlTemplate = [];
            $textTemplate = [];

            foreach ($this->locales as $locale) {
                App::setLocale($locale);

                $subject[$locale] = $this->getTranslation("{$translationNamespace}/{$name}.subject");
                $htmlTemplate[$locale] = $this->getTranslation("{$translationNamespace}/{$name}.html_template");
                $textTemplate[$locale] = $this->getTranslation("{$translationNamespace}/{$name}.text_template");
            }

            $this->createOrUpdateTemplate($name, $contract, $subject, $htmlTemplate, $textTemplate);
        }
    }

    protected function getTranslation(string $key): string
    {
        $value = __($key);

        if ($value === $key) {
            throw new RuntimeException("Missing translation for key: {$key}");
        }

        return $value;
    }

    protected function createOrUpdateTemplate(string $name, ?string $contract, array $subject, array $htmlTemplate, array $textTemplate): void
    {
        MailTemplates::updateOrCreate(
            ['name' => $name],
            [
                'mailable' => StandardMailable::class,
                'contract' => $contract, // kan være null
                'subject' => $subject,
                'html_template' => $htmlTemplate,
                'text_template' => $textTemplate,
            ]
        );
    }
}
