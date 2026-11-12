<?php

return [
    'fortress_gate' => [
        'title' => 'Fortress gate',
        'text' => 'Beyond the ruined gate, a faint orange light flickers through a crack in the stone.',
        'choices' => [
            'enter_gate' => ['label' => 'Enter through the gate', 'next' => 'courtyard'],
            'search_wall' => ['label' => 'Search along the wall', 'next' => 'outer_wall'],
        ],
    ],
    'courtyard' => [
        'title' => 'Ashen courtyard',
        'text' => 'A wounded watchkeeper calls from beside a broken fountain. An inner gate bars the direct route.',
        'choices' => [
            'help_watchkeeper' => [
                'label' => 'Help the watchkeeper',
                'next' => 'watchkeeper',
                'points' => 10,
                'alignment' => 2,
            ],
            'force_gate' => [
                'label' => 'Force the inner gate',
                'next' => 'barracks',
                'strength' => 30,
            ],
            'take_side_door' => ['label' => 'Take the side door', 'next' => 'chapel'],
        ],
    ],
    'outer_wall' => [
        'title' => 'Outer wall',
        'text' => 'A loose grate leads beneath the wall. A stone stair climbs toward the old chapel.',
        'choices' => [
            'open_grate' => [
                'label' => 'Open the grate with your lockpick',
                'next' => 'tunnels',
                'item' => 'Lockpick',
            ],
            'climb_stair' => ['label' => 'Climb to the chapel', 'next' => 'chapel'],
        ],
    ],
    'watchkeeper' => [
        'title' => 'The watchkeeper',
        'text' => 'The watchkeeper catches his breath. He says the archive holds a key to the fortress beacon.',
        'choices' => [
            'follow_directions' => ['label' => 'Follow his directions', 'next' => 'archive'],
            'head_to_barracks' => ['label' => 'Search the barracks instead', 'next' => 'barracks'],
        ],
    ],
    'chapel' => [
        'title' => 'Ruined chapel',
        'text' => 'A scorched mural shows the ember burning in a beacon high above the fortress.',
        'choices' => [
            'shield_with_ward' => [
                'label' => 'Use your ward and cross the burning aisle',
                'next' => 'archive',
                'item' => 'Ward',
            ],
            'take_stairs' => ['label' => 'Follow the stairs underground', 'next' => 'tunnels'],
        ],
    ],
    'barracks' => [
        'title' => 'Empty barracks',
        'text' => 'Smoke pours across the room. You can hurry through it or take the longer archive passage.',
        'choices' => [
            'cross_smoke' => [
                'label' => 'Run through the smoke',
                'next' => 'bridge',
                'health' => -25,
                'alignment' => -1,
            ],
            'take_passage' => ['label' => 'Take the archive passage', 'next' => 'archive'],
        ],
    ],
    'tunnels' => [
        'title' => 'Drainage tunnels',
        'text' => 'Water rises around your boots. An old maintenance shaft leads upward.',
        'choices' => [
            'climb_shaft' => ['label' => 'Climb the maintenance shaft', 'next' => 'archive'],
            'wade_ahead' => [
                'label' => 'Wade straight toward the bridge',
                'next' => 'bridge',
                'health' => -15,
            ],
        ],
    ],
    'archive' => [
        'title' => 'Fortress archive',
        'text' => 'The Beacon Key hangs beside a map of the upper tower. The bridge is just beyond the archive.',
        'choices' => [
            'take_key' => [
                'label' => 'Take the Beacon Key',
                'next' => 'bridge',
                'gain' => 'Beacon Key',
                'points' => 5,
            ],
            'leave_key' => ['label' => 'Leave the key and cross the bridge', 'next' => 'bridge'],
        ],
    ],
    'bridge' => [
        'title' => 'Fractured bridge',
        'text' => 'The bridge overlooks a shaft full of hot ash. A narrow ledge offers a slower crossing.',
        'choices' => [
            'cross_ledge' => ['label' => 'Follow the ledge', 'next' => 'guardian'],
            'jump_gap' => [
                'label' => 'Leap across the broken span',
                'next' => 'guardian',
                'health' => -35,
                'alignment' => -1,
            ],
        ],
    ],
    'guardian' => [
        'title' => 'The ember guardian',
        'text' => 'The guardian asks whether the ember is for your village or for you alone.',
        'choices' => [
            'promise_return' => [
                'label' => 'Promise to protect the village',
                'next' => 'ember_vault',
                'points' => 10,
                'alignment' => 2,
            ],
            'push_past' => [
                'label' => 'Push past the guardian',
                'next' => 'ember_vault',
                'health' => -30,
                'alignment' => -2,
            ],
        ],
    ],
    'ember_vault' => [
        'title' => 'Ember vault',
        'text' => 'The last ember burns inside a stone bowl. A stair behind it leads to the beacon.',
        'choices' => [
            'return_ember' => [
                'label' => 'Carry the ember home',
                'next' => 'heroic_victory',
                'points' => 20,
                'alignment' => 2,
            ],
            'seize_ember' => [
                'label' => 'Claim the ember for yourself',
                'next' => 'tragic_failure',
                'alignment' => -2,
            ],
            'take_tower_stair' => ['label' => 'Climb to the beacon', 'next' => 'beacon_tower'],
        ],
    ],
    'beacon_tower' => [
        'title' => 'Beacon tower',
        'text' => 'The beacon door is sealed. Through its ironwork you can see the village below.',
        'choices' => [
            'light_beacon' => [
                'label' => 'Unlock the beacon and light it',
                'next' => 'secret_path',
                'item' => 'Beacon Key',
                'points' => 25,
                'alignment' => 2,
            ],
            'return_home' => [
                'label' => 'Take the ember home',
                'next' => 'heroic_victory',
                'points' => 20,
                'alignment' => 2,
            ],
        ],
    ],
    'heroic_victory' => [
        'title' => 'Heroic Victory',
        'text' => 'You return the ember to the village hearth. Its light holds through the night.',
    ],
    'tragic_failure' => [
        'title' => 'Tragic Failure',
        'text' => 'The fortress claims the ember. The village watch waits for a light that never comes.',
    ],
    'secret_path' => [
        'title' => 'Secret Path',
        'text' => 'The restored beacon lights every hearth in the valley. The fortress has a purpose again.',
    ],
];