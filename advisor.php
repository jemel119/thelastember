<?php

function ember_choice_preview(array $choice, int $health): string
{
    $effects = [];
    $healthChange = $choice['health'] ?? 0;
    $points = $choice['points'] ?? 0;

    if ($healthChange < 0) {
        $effects[] = $health + $healthChange <= 0
            ? 'This would reduce your health to zero.'
            : 'Costs ' . abs($healthChange) . ' health.';
    } elseif ($healthChange > 0) {
        $effects[] = 'Restores ' . $healthChange . ' health.';
    }

    if (isset($choice['gain'])) {
        $effects[] = 'Adds ' . $choice['gain'] . ' to your inventory.';
    }

    if ($points > 0) {
        $effects[] = 'Earns ' . $points . ' story points.';
    }

    return $effects === []
        ? 'No immediate change to health, inventory, or story points.'
        : implode(' ', $effects);
}

function ember_alignment(array $choices): array
{
    $score = 0;

    foreach ($choices as $choice) {
        $score += $choice['alignment'] ?? 0;
    }

    if ($score >= 3) {
        return [
            'score' => $score,
            'label' => 'Protective',
            'hint' => 'Your choices suggest the village can count on you.',
        ];
    }

    if ($score <= -3) {
        return [
            'score' => $score,
            'label' => 'Ruthless',
            'hint' => 'Your choices suggest you may put your own goal first.',
        ];
    }

    return [
        'score' => $score,
        'label' => 'Uncertain',
        'hint' => 'Your choices leave your intentions open.',
    ];
}