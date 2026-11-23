<?php

/*
|--------------------------------------------------------------------------
| Local projects (Canada)
|--------------------------------------------------------------------------
|
| Content for the three local-project pages (resources/views/local-project.blade.php).
| Routes: routes/web.php (local.youth / local.food / local.women), switched on per
| region with 'show_local_projects' in config/countries.php. Edit the wording here —
| no code change is needed.
|
*/

return [

    'youth' => [
        'title' => 'Youth Leadership & Community Development Initiative',
        'short' => 'Youth Leadership & Community Development',
        'cause' => 'Youth Leadership & Community Development',
        'image' => 'images/naeemeducation.jpeg',
        'heading' => 'Growing Tomorrow\'s <span class="text-cream">Community Leaders</span>',
        'quote' => 'The best of people are those who are most beneficial to people.',
        'intro' => [
            'The Naeem Foundation Youth Leadership & Community Development Initiative is a local project that helps young people grow in confidence, character and leadership, and use their skills to serve the community around them.',
            'By investing in young people today, we help build a stronger, more caring community for everyone tomorrow.',
        ],
        'focus_heading' => 'What the Initiative Is About',
        'focus' => [
            ['title' => 'Leadership & Character', 'text' => 'Helping young people build confidence, good character and the ability to lead.', 'icon' => '<path d="M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5L12 3z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Mentorship', 'text' => 'Connecting youth with positive role models who guide and encourage them.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Community Service', 'text' => 'Encouraging young people to give back and make a real difference locally.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Skills for the Future', 'text' => 'Supporting learning and personal development that opens doors.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ],
        'cta_eyebrow' => 'Invest in young people',
        'cta_title' => 'Help Us Raise the Next Generation of Leaders',
        'cta_text' => 'Your gift supports this local initiative and the young people it serves. Give today, or volunteer your time as a mentor or helper.',
    ],

    'food' => [
        'title' => 'Food Assistance Program',
        'short' => 'Food Assistance Program',
        'cause' => 'Food Assistance Program',
        'image' => 'images/Food-1920.jpg',
        'heading' => 'No Neighbour Should Go <span class="text-cream">Hungry</span>',
        'quote' => 'Feed the hungry, and you feed the soul.',
        'intro' => [
            'The Naeem Foundation Food Assistance Program is a local project that supports families in our community who are struggling to put food on the table.',
            'We believe that everyone deserves access to nutritious food, and that help should be given with dignity and care.',
        ],
        'focus_heading' => 'What the Program Is About',
        'focus' => [
            ['title' => 'Food Support', 'text' => 'Helping local families access the food they need.', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Dignity & Care', 'text' => 'Respectful help for every household, with no judgement.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Community Partnership', 'text' => 'Working with the community and volunteers to reach families in need.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Everyday Essentials', 'text' => 'Nutritious food and basic essentials that make a daily difference.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ],
        'cta_eyebrow' => 'Feed a family',
        'cta_title' => 'Help Put Food on a Neighbour\'s Table',
        'cta_text' => 'Your gift supports this local program. Give today, or volunteer your time to help us reach more families.',
    ],

    'women' => [
        'title' => 'Women\'s Growth & Community Project (WGCP)',
        'short' => 'Women\'s Growth & Community Project',
        'cause' => 'Women\'s Growth & Community Project',
        'image' => 'images/hoodsubstainliveli.jpeg',
        'heading' => 'Empowering Women, <span class="text-cream">Strengthening Community</span>',
        'quote' => 'A strong community is built by strong, supported women.',
        'intro' => [
            'The Naeem Foundation Women\'s Growth & Community Project (WGCP) is a local project that creates opportunities for women to learn, connect, grow and take part fully in community life.',
            'When women are supported and encouraged, their families and the whole community benefit.',
        ],
        'focus_heading' => 'What the Project Is About',
        'focus' => [
            ['title' => 'Learning & Skills', 'text' => 'Opportunities for women to learn and develop new skills.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Community & Connection', 'text' => 'A welcoming space to meet, share and support one another.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Wellbeing', 'text' => 'Encouraging the health and wellbeing of women and their families.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Confidence & Leadership', 'text' => 'Helping women find their voice and take on leading roles.', 'icon' => '<path d="M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5L12 3z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ],
        'cta_eyebrow' => 'Invest in women',
        'cta_title' => 'Help Women Grow and Their Community Thrive',
        'cta_text' => 'Your gift supports this local project. Give today, or volunteer your time to help it grow.',
    ],

];
