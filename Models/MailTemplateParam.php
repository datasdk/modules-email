<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class MailTemplateParam extends Model
{
    use HasFactory;

    protected $table = 'mail_template_params'; // Eksplicit tabelnavn

    protected $fillable = [
        'template_id',
        'name',
        'label'
    ];

    public function template()
    {
        return $this->belongsTo(MailTemplate::class, 'template_id');
    }

    /**
     * Opdater eller indsæt mail template params fra et array
     * @param int $templateId
     * @param array|null $params
     */
    public static function updateFromEmailParams(int $templateId, ?array $params): void
    {
        if (!$templateId || empty($params) || !is_array($params)) {
            return;
        }

        try {
            // Flad alle nested arrays til key => value par
            $flatParams = array_keys(Arr::dot($params));

            foreach ($flatParams as $name) {

                $exists = self::where('template_id', $templateId)
                    ->where('name', $name)
                    ->exists();

                if (!$exists) {
                    self::create([
                        'template_id' => $templateId,
                        'name'        => $name,
                        'label'       => str_replace('.', ' / ', $name),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to update MailTemplateParam from email params', [
                'template_id' => $templateId,
                'error'       => $e->getMessage(),
            ]);
        }
    }
}
