<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use DateTime;

class LDate extends Shortcode
{
    public function __construct()
    {
        parent::__construct("date");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attr = $config['attributes'] ?? [];
        $action = $config['sections'][0] ?? '';
        $default = $attr['default'] ?? "";
        $output = "";

        switch ($action) {
            case 'format':
                $date = $this->replaceVariables($attr['main'] ?? $default);
                $format = $attr['format'] ?? 'Y-m-d';
                $formattedDate = date($format, strtotime($date));
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $formattedDate);
                } else {
                    $output = $formattedDate;
                }
                break;

            case 'current':
                $currentDate = date('Y-m-d');
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $currentDate);
                } else {
                    $output = $currentDate;
                }
                break;

            case 'timestamp':
                $timestamp = time();
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $timestamp);
                } else {
                    $output = $timestamp;
                }
                break;

            case 'get':
                $format = $attr['format'] ?? 'Y-m-d H:i:s';
                $currentFormattedDate = date($format);
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $currentFormattedDate);
                } else {
                    $output = $currentFormattedDate;
                }
                break;

            case 'time':
                $currentTime = date('H:i:s');
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $currentTime);
                } else {
                    $output = $currentTime;
                }
                break;

            case 'difference':
                $date1 = $this->replaceVariables($attr['date1'] ?? $default);
                $date2 = $this->replaceVariables($attr['date2'] ?? $default);
                $unit = $attr['unit'] ?? 'days';
                $diff = $this->calculateDateDifference($date1, $date2, $unit);
                if (isset($attr['set'])) {
                    $this->set($attr['set'], $diff);
                } else {
                    $output = $diff;
                }
                break;

            default:
                break;
        }

        return $output;
    }

    /**
     * @throws \Exception
     */
    private function calculateDateDifference(string $date1, string $date2, string $unit = 'days'): int
    {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);

        return match ($unit) {
            'years' => $interval->y,
            'months' => $interval->m + ($interval->y * 12),
            'hours' => $interval->h + ($interval->days * 24),
            'minutes' => ($interval->h + ($interval->days * 24)) * 60 + $interval->i,
            'seconds' => (($interval->h + ($interval->days * 24)) * 60 + $interval->i) * 60 + $interval->s,
            default => $interval->days,
        };
    }
}