<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Knowledge base with comprehensive crop care guides
$crop_guides = [
    'ধান' => [
        'icon' => 'fa-seedling',
        'color' => 'rgba(40, 167, 69, 0.85)',
        'duration' => '১২০-১৫০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১৫ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ ও মই দিয়ে সমতল করুন',
                    'শেষ চাষের সময় জমিতে জৈব সার মিশিয়ে দিন',
                    'প্রতি বিঘায় ৮-১০ কুইন্টাল গোবর সার প্রয়োগ করুন',
                    'জমিতে পানি ধরে রাখার ব্যবস্থা করুন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৭ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'প্রতি বিঘায় ৮-১০ কেজি মানসম্পন্ন বীজ ব্যবহার করুন',
                    'বীজ শোধনের জন্য ভিটাভেক্স ব্যবহার করুন (প্রতি কেজি বীজে ২.৫ গ্রাম)',
                    'সারিতে বপনের ক্ষেত্রে সারি থেকে সারির দূরত্ব ২০ সেমি রাখুন',
                    'বীজ বপনের পর হালকা পানি সেচ দিন'
                ]
            ],
            [
                'title' => 'প্রাথমিক পরিচর্যা',
                'time' => '৮-৩০ দিন',
                'icon' => 'fa-leaf',
                'tasks' => [
                    'চারা গজানোর ১৫ দিন পর প্রথম ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ৮ কেজি)',
                    'আগাছা পরিষ্কার করুন (১৫-২০ দিন পর)',
                    'নিয়মিত জমি পরিদর্শন করুন',
                    'প্রয়োজনমতো হালকা সেচ দিন (২-৩ ইঞ্চি পানি)'
                ]
            ],
            [
                'title' => 'বৃদ্ধি পর্যায়',
                'time' => '৩১-৬০ দিন',
                'icon' => 'fa-chart-line',
                'tasks' => [
                    'দ্বিতীয় ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ৮ কেজি)',
                    'এমওপি সার প্রয়োগ করুন (বিঘা প্রতি ১০ কেজি)',
                    'টিএসপি সার প্রয়োগ করুন (বিঘা প্রতি ১৫ কেজি)',
                    'নিয়মিত ৩-৪ ইঞ্চি পানি রাখুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'ব্লাস্ট রোগ',
                'symptoms' => 'পাতায় চোখের মতো দাগ, গাছ শুকিয়ে যাওয়া',
                'prevention' => 'রোগ প্রতিরোধী জাত ব্যবহার, সুষম সার প্রয়োগ',
                'treatment' => 'Tilt 250 EC (৫ মিলি/১০ লিটার পানি) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'মাজরা পোকা',
                'symptoms' => 'কাণ্ডের ভিতরে পোকা, মরা ডিগ দেখা যায়',
                'treatment' => 'Furadan 3G (বিঘা প্রতি ৪ কেজি) প্রয়োগ করুন'
            ]
        ],
        'tips' => [
            'সকাল ১০টা থেকে বিকেল ৪টার মধ্যে কীটনাশক স্প্রে করবেন না',
            'কীটনাশক ব্যবহারের সময় মাস্ক ও গ্লাভস পরুন'
        ]
    ],
    'গম' => [
        'icon' => 'fa-wheat-awn',
        'color' => 'rgba(255, 193, 7, 0.85)',
        'duration' => '১১০-১৩০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৪-৫ বার চাষ করে ঝুরঝুরে করুন',
                    'জৈব সার প্রয়োগ করুন (বিঘা প্রতি ১০ কুইন্টাল)',
                    'শেষ চাষে TSP ও MoP সার মিশিয়ে দিন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ১৫-১৮ কেজি বীজ ব্যবহার করুন',
                    'সারিতে বপন করুন (সারির দূরত্ব ২০ সেমি)'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Rust রোগ',
                'symptoms' => 'পাতায় মরিচা রঙের গুঁড়ো দাগ',
                'prevention' => 'রোগমুক্ত বীজ ব্যবহার',
                'treatment' => 'Tilt 250 EC স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'Aphid (জাব পোকা)',
                'symptoms' => 'পাতায় ছোট সবুজ পোকা',
                'treatment' => 'Admire 200 SL স্প্রে করুন'
            ]
        ],
        'tips' => [
            'নভেম্বর মাসের প্রথম সপ্তাহ বপনের উপযুক্ত সময়',
            'দেরিতে বপন করলে ফলন কমে যায়'
        ]
    ],
    'আলু' => [
        'icon' => 'fa-seedling',
        'color' => 'rgba(139, 69, 19, 0.85)',
        'duration' => '৯০-১১০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'রোপণের ২০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৫-৬ বার চাষ করে ঝুরঝুরে করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ১৫ কুইন্টাল)',
                    'শেষ চাষে সম্পূর্ণ TSP, MoP মিশিয়ে দিন',
                    'উঁচু বেড তৈরি করুন (১৫-২০ সেমি)'
                ]
            ],
            [
                'title' => 'বীজ আলু রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ৩০০-৩৫০ কেজি বীজ আলু প্রয়োজন',
                    'সারিতে রোপণ (সারির দূরত্ব ৬০ সেমি)',
                    'বীজ আলু ৫-৭ সেমি গভীরে রোপণ করুন'
                ]
            ],
            [
                'title' => 'প্রথম সার প্রয়োগ',
                'time' => '২১-৩০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'প্রথম ইউরিয়া প্রয়োগ (বিঘা প্রতি ১৮ কেজি)',
                    'মাটি তুলে দিন',
                    'Late Blight এর জন্য পর্যবেক্ষণ করুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Late Blight',
                'symptoms' => 'পাতায় কালো দাগ, পাতা ঝলসে যায়',
                'prevention' => 'রোগমুক্ত বীজ, সঠিক দূরত্ব',
                'treatment' => 'Secure 600 WG (২ গ্রাম/লিটার) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'জাব পোকা (Aphid)',
                'symptoms' => 'পাতার নিচে সবুজ পোকা',
                'treatment' => 'Admire 200 SL (০.৫ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'নভেম্বর মাসে রোপণের উপযুক্ত সময়',
            'ঠান্ডা ও অন্ধকার জায়গায় সংরক্ষণ করুন'
        ]
    ],
    'পাট' => [
        'icon' => 'fa-leaf',
        'color' => 'rgba(76, 175, 80, 0.85)',
        'duration' => '১২০-১৫০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১৫ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ৮ কুইন্টাল)',
                    'মাটি সমতল করুন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৭ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ২-৩ কেজি বীজ ব্যবহার করুন',
                    'ছিটিয়ে বা সারিতে বপন করুন',
                    'মার্চ-এপ্রিল মাস বপনের উপযুক্ত সময়'
                ]
            ],
            [
                'title' => 'পরিচর্যা',
                'time' => '৩০-৬০ দিন',
                'icon' => 'fa-leaf',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১০ কেজি)',
                    'আগাছা পরিষ্কার করুন',
                    'প্রয়োজনে সেচ দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'কাণ্ড পচা রোগ',
                'symptoms' => 'কাণ্ডের গোড়া পচে যায়',
                'prevention' => 'জলাবদ্ধতা এড়ান',
                'treatment' => 'Bavistin প্রয়োগ করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'বিছা পোকা',
                'symptoms' => 'পাতা খেয়ে ফেলে',
                'treatment' => 'Malathion 57 EC স্প্রে করুন'
            ]
        ],
        'tips' => [
            'জাগ দেওয়ার ১৫-২০ দিন পর কাটুন',
            'রৌদ্রজ্জ্বল দিনে কাটা ভালো'
        ]
    ],
    'আখ' => [
        'icon' => 'fa-candy-cane',
        'color' => 'rgba(156, 39, 176, 0.85)',
        'duration' => '১০-১২ মাস',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'রোপণের ৩০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি গভীরভাবে চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ২০ কুইন্টাল)',
                    'নালা তৈরি করুন (৯০-১২০ সেমি দূরত্ব)'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-১০ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '২-৩ চোখ বিশিষ্ট কাটিং ব্যবহার করুন',
                    'নালায় শুইয়ে রোপণ করুন',
                    'অক্টোবর-নভেম্বর রোপণের উপযুক্ত সময়'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '৩০-৬০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'প্রথম ইউরিয়া প্রয়োগ (বিঘা প্রতি ৩০ কেজি)',
                    'TSP প্রয়োগ করুন',
                    'মাটি তুলে দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'লাল পচা রোগ',
                'symptoms' => 'পাতায় লাল দাগ, কাণ্ড পচে',
                'prevention' => 'সুস্থ চারা ব্যবহার করুন',
                'treatment' => 'আক্রান্ত গাছ তুলে পুড়িয়ে ফেলুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'কাণ্ড ছিদ্রকারী পোকা',
                'symptoms' => 'কাণ্ডে ছিদ্র দেখা যায়',
                'treatment' => 'Furadan 3G প্রয়োগ করুন'
            ]
        ],
        'tips' => [
            'নিয়মিত সেচ দিন বিশেষ করে গ্রীষ্মকালে',
            '১০-১২ মাস পর কাটার উপযুক্ত হয়'
        ]
    ],
    'পেঁয়াজ' => [
        'icon' => 'fa-circle',
        'color' => 'rgba(156, 39, 176, 0.85)',
        'duration' => '৯০-১২০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'রোপণের ১৫ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৪-৫ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ১০ কুইন্টাল)',
                    'উঁচু বেড তৈরি করুন'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '৩০-৪০ দিনের চারা ব্যবহার করুন',
                    'সারিতে রোপণ (সারির দূরত্ব ২০ সেমি)',
                    'নভেম্বর-ডিসেম্বর রোপণের সময়'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '৩০-৪৫ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১৫ কেজি)',
                    'নিয়মিত সেচ দিন',
                    'আগাছা পরিষ্কার রাখুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Purple Blotch',
                'symptoms' => 'পাতায় বেগুনি দাগ',
                'prevention' => 'রোগমুক্ত চারা ব্যবহার',
                'treatment' => 'Rovral 50 WP স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'থ্রিপস পোকা',
                'symptoms' => 'পাতা সাদা হয়ে যায়',
                'treatment' => 'Admire 200 SL স্প্রে করুন'
            ]
        ],
        'tips' => [
            'পাতা হলুদ হলে সেচ বন্ধ করুন',
            'রোদে শুকিয়ে ঠান্ডা স্থানে সংরক্ষণ করুন'
        ]
    ],
    'টমেটো' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(220, 53, 69, 0.85)',
        'duration' => '৭০-৯০ দিন',
        'stages' => [
            [
                'title' => 'বীজতলা তৈরি',
                'time' => 'রোপণের ৩০ দিন আগে',
                'icon' => 'fa-seedling',
                'tasks' => [
                    'উঁচু বীজতলা তৈরি করুন',
                    'জৈব সার ও বালি মিশিয়ে মাটি তৈরি করুন',
                    'বীজ শোধনে Bavistin ব্যবহার করুন'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '২৫-৩০ দিনের চারা রোপণ করুন',
                    'সারিতে রোপণ (দূরত্ব ৭৫x৬০ সেমি)',
                    'বিকেলে রোপণ করুন'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ ও পরিচর্যা',
                'time' => '২১-৫০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১৫ কেজি)',
                    'নিয়মিত সেচ দিন',
                    'খুঁটি দিন (লম্বা জাতের জন্য)'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Late Blight',
                'symptoms' => 'পাতা ও ফলে কালো দাগ',
                'prevention' => 'রোগমুক্ত চারা ব্যবহার',
                'treatment' => 'Secure 600 WG স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'ফল ছিদ্রকারী পোকা',
                'symptoms' => 'ফলে ছিদ্র, ভিতরে পোকা',
                'treatment' => 'Coragen 20 SC স্প্রে করুন'
            ]
        ],
        'tips' => [
            'শীতকালে চারা রোপণের উপযুক্ত সময়',
            'সকালের শিশির শুকিয়ে গেলে ফল তুলুন'
        ]
    ],
    'ভুট্টা' => [
        'icon' => 'fa-wheat-awn',
        'color' => 'rgba(255, 152, 0, 0.85)',
        'duration' => '১০০-১২০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১৫ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ১০ কুইন্টাল)',
                    'শেষ চাষে TSP ও MoP মিশিয়ে দিন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৭ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ৪-৫ কেজি বীজ ব্যবহার করুন',
                    'সারিতে বপন (দূরত্ব ৬০x২৫ সেমি)',
                    'বীজ ৩-৪ সেমি গভীরে বপন করুন'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '৩০-৬০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'প্রথম ইউরিয়া প্রয়োগ (বিঘা প্রতি ১৫ কেজি)',
                    'দ্বিতীয় ইউরিয়া প্রয়োগ (বিঘা প্রতি ১৫ কেজি)',
                    'নিয়মিত সেচ দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Leaf Blight',
                'symptoms' => 'পাতায় লম্বা বাদামী দাগ',
                'prevention' => 'রোগমুক্ত বীজ ব্যবহার',
                'treatment' => 'Bavistin 50 WP স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'Fall Army Worm',
                'symptoms' => 'পাতায় গোলাকার ছিদ্র',
                'treatment' => 'Coragen 20 SC স্প্রে করুন'
            ]
        ],
        'tips' => [
            'নভেম্বর-ডিসেম্বর বপনের উপযুক্ত সময়',
            'হাইব্রিড জাত ব্যবহার করলে ফলন বেশি'
        ]
    ],
    'সরিষা' => [
        'icon' => 'fa-leaf',
        'color' => 'rgba(255, 193, 7, 0.85)',
        'duration' => '৮০-৯০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ৮ কুইন্টাল)',
                    'মাটি ঝুরঝুরে করুন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ৩-৪ কেজি বীজ ব্যবহার করুন',
                    'সারিতে বপন (দূরত্ব ৩০ সেমি)',
                    'অক্টোবর-নভেম্বর বপনের সময়'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '২৫-৩৫ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১০ কেজি)',
                    'আগাছা পরিষ্কার করুন',
                    'প্রয়োজনে সেচ দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Alternaria Blight',
                'symptoms' => 'পাতায় গাঢ় বাদামী দাগ',
                'prevention' => 'সুষম সার প্রয়োগ',
                'treatment' => 'Rovral 50 WP স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'জাব পোকা',
                'symptoms' => 'ফুল ও ফলে পোকা লাগে',
                'treatment' => 'Admire 200 SL স্প্রে করুন'
            ]
        ],
        'tips' => [
            'শুঁটি হলুদ হলে কাটার উপযুক্ত',
            'রৌদ্রজ্জ্বল দিনে কাটুন'
        ]
    ],
    'মসুর ডাল' => [
        'icon' => 'fa-seedling',
        'color' => 'rgba(255, 87, 34, 0.85)',
        'duration' => '১০০-১১০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ৬ কুইন্টাল)',
                    'মাটি সমতল করুন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ১৫-২০ কেজি বীজ ব্যবহার করুন',
                    'সারিতে বপন (দূরত্ব ৩০ সেমি)',
                    'নভেম্বর মাসে বপন করুন'
                ]
            ],
            [
                'title' => 'পরিচর্যা',
                'time' => '৩০-৫০ দিন',
                'icon' => 'fa-leaf',
                'tasks' => [
                    'আগাছা পরিষ্কার করুন',
                    'প্রয়োজনে একবার সেচ দিন',
                    'ফুল আসার সময় যত্ন নিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'Rust রোগ',
                'symptoms' => 'পাতায় মরিচা রঙের দাগ',
                'prevention' => 'রোগমুক্ত বীজ ব্যবহার',
                'treatment' => 'Tilt 250 EC স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'ফল ছিদ্রকারী পোকা',
                'symptoms' => 'ফলে ছিদ্র দেখা যায়',
                'treatment' => 'Karate 2.5 EC স্প্রে করুন'
            ]
        ],
        'tips' => [
            'শুঁটি হলুদ হলে কাটুন',
            'ভালোভাবে শুকিয়ে সংরক্ষণ করুন'
        ]
    ],
    'রসুন' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(156, 39, 176, 0.85)',
        'duration' => '১৪০-১৫০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'রোপণের ২০ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৪-৫ বার চাষ করে ঝুরঝুরে করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ১২ কুইন্টাল)',
                    'উঁচু বেড তৈরি করুন (প্রতি বেড ১ মিটার চওড়া)'
                ]
            ],
            [
                'title' => 'কোয়া রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'মাঝারি আকারের সুস্থ কোয়া নির্বাচন করুন',
                    'সারিতে রোপণ (সারির দূরত্ব ২০ সেমি, কোয়ার দূরত্ব ১০ সেমি)',
                    'অক্টোবর-নভেম্বর রোপণের উপযুক্ত সময়'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ ও পরিচর্যা',
                'time' => '৩০-৬০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'প্রথম ইউরিয়া প্রয়োগ (বিঘা প্রতি ১২ কেজি)',
                    'দ্বিতীয় ইউরিয়া প্রয়োগ (বিঘা প্রতি ১২ কেজি)',
                    'নিয়মিত আগাছা পরিষ্কার করুন',
                    'প্রয়োজনমতো সেচ দিন (৭-১০ দিন পর পর)'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'পার্পল ব্লচ',
                'symptoms' => 'পাতায় বেগুনি দাগ, পাতা শুকিয়ে যায়',
                'prevention' => 'রোগমুক্ত কোয়া ব্যবহার, জলাবদ্ধতা এড়িয়ে চলুন',
                'treatment' => 'Rovral 50 WP (২ গ্রাম/লিটার পানি) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'থ্রিপস পোকা',
                'symptoms' => 'পাতা সাদা হয়ে যায়, বৃদ্ধি কমে যায়',
                'treatment' => 'Admire 200 SL (০.৫ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'পাতা হলুদ হয়ে শুকিয়ে গেলে তোলার সময়',
            'তোলার পর ৩-৪ দিন রোদে শুকিয়ে সংরক্ষণ করুন',
            'শীতল ও শুষ্ক জায়গায় রাখুন'
        ]
    ],
    'বেগুন' => [
        'icon' => 'fa-pepper-hot',
        'color' => 'rgba(103, 58, 183, 0.85)',
        'duration' => '৭০-৮০ দিন',
        'stages' => [
            [
                'title' => 'বীজতলা তৈরি',
                'time' => 'রোপণের ৩৫ দিন আগে',
                'icon' => 'fa-seedling',
                'tasks' => [
                    'উঁচু বীজতলা তৈরি করুন (১ মিটার x ৩ মিটার)',
                    'জৈব সার ও মাটি মিশিয়ে বীজতলা তৈরি করুন',
                    'বীজ শোধনে Provax 200 WP ব্যবহার করুন'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '৩০-৩৫ দিনের চারা রোপণ করুন',
                    'সারিতে রোপণ (দূরত্ব ৭৫x৬০ সেমি)',
                    'বিকেলে রোপণ করে হালকা সেচ দিন'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '২০-৫০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'প্রথম ইউরিয়া প্রয়োগ (বিঘা প্রতি ১২ কেজি)',
                    'দ্বিতীয় ইউরিয়া প্রয়োগ (বিঘা প্রতি ১২ কেজি)',
                    'নিয়মিত সেচ ও আগাছা পরিষ্কার করুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'ড্যাম্পিং অফ',
                'symptoms' => 'চারা গোড়া থেকে পচে যায়',
                'prevention' => 'বীজশোধন করুন, অতিরিক্ত পানি এড়িয়ে চলুন',
                'treatment' => 'Bavistin 50 WP (২ গ্রাম/লিটার) দিয়ে মাটি শোধন করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'ফল ও কাণ্ড ছিদ্রকারী পোকা',
                'symptoms' => 'ফল ও কাণ্ডে ছিদ্র, ভিতরে পোকা',
                'treatment' => 'Coragen 20 SC (০.৩ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'ফল উজ্জ্বল রঙের হলে তোলার উপযুক্ত',
            'নিয়মিত ফল সংগ্রহ করলে ফলন বেশি হয়',
            'রোগাক্রান্ত ফল দেখামাত্র ফেলে দিন'
        ]
    ],
    'মরিচ' => [
        'icon' => 'fa-pepper-hot',
        'color' => 'rgba(244, 67, 54, 0.85)',
        'duration' => '৮০-৯০ দিন',
        'stages' => [
            [
                'title' => 'বীজতলা প্রস্তুতি',
                'time' => 'রোপণের ৪০ দিন আগে',
                'icon' => 'fa-seedling',
                'tasks' => [
                    'উঁচু বীজতলা তৈরি করুন',
                    'জৈব সার ও বালি মিশিয়ে মাটি তৈরি করুন',
                    'বীজ রাতভর পানিতে ভিজিয়ে রাখুন'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '৩৫-৪০ দিনের চারা রোপণ করুন',
                    'সারিতে রোপণ (দূরত্ব ৬০x৪৫ সেমি)',
                    'মেঘাচ্ছন্ন দিনে রোপণ করুন'
                ]
            ],
            [
                'title' => 'সার ও পরিচর্যা',
                'time' => '২৫-৫৫ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১৫ কেজি - দুই কিস্তিতে)',
                    'নিয়মিত সেচ দিন (৭-১০ দিন পর পর)',
                    'আগাছা পরিষ্কার রাখুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'এনথ্রাকনোজ',
                'symptoms' => 'ফলে কালো দাগ, ফল পচে যায়',
                'prevention' => 'রোগমুক্ত বীজ ব্যবহার, সুষম সার প্রয়োগ',
                'treatment' => 'Secure 600 WG (২ গ্রাম/লিটার) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'জাব পোকা',
                'symptoms' => 'পাতা কুঁকড়ে যায়, গাছের বৃদ্ধি কমে',
                'treatment' => 'Actara 25 WG (০.৩ গ্রাম/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'সবুজ ও লাল উভয় অবস্থায় মরিচ তোলা যায়',
            'নিয়মিত তুললে ফলন বেশি হয়',
            'শুকনো মরিচ রোদে ভালো শুকিয়ে সংরক্ষণ করুন'
        ]
    ],
    'শিম' => [
        'icon' => 'fa-leaf',
        'color' => 'rgba(76, 175, 80, 0.85)',
        'duration' => '৭০-৮০ দিন',
        'stages' => [
            [
                'title' => 'জমি প্রস্তুতি',
                'time' => 'বপনের ১৫ দিন আগে',
                'icon' => 'fa-tractor',
                'tasks' => [
                    'জমি ৩-৪ বার চাষ করুন',
                    'জৈব সার প্রয়োগ (বিঘা প্রতি ১০ কুইন্টাল)',
                    'মাটি ঝুরঝুরে করে মই দিন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'বিঘা প্রতি ৮-১০ কেজি বীজ ব্যবহার করুন',
                    'সারিতে বপন (দূরত্ব ৬০x৩০ সেমি)',
                    'সেপ্টেম্বর-অক্টোবর বপনের সময়'
                ]
            ],
            [
                'title' => 'পরিচর্যা',
                'time' => '২০-৫০ দিন',
                'icon' => 'fa-leaf',
                'tasks' => [
                    'খুঁটি বা মাচা তৈরি করুন',
                    'ইউরিয়া সার প্রয়োগ (বিঘা প্রতি ১০ কেজি)',
                    'নিয়মিত সেচ দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'পাউডারি মিলডিউ',
                'symptoms' => 'পাতায় সাদা পাউডারের মতো আবরণ',
                'prevention' => 'সুষম সার ব্যবহার, অতিরিক্ত ঘন বপন এড়িয়ে চলুন',
                'treatment' => 'Tilt 250 EC (০.৫ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'ফল ছিদ্রকারী পোকা',
                'symptoms' => 'শুঁটিতে ছিদ্র, ভিতরে কীড়া',
                'treatment' => 'Karate 2.5 EC (২ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'শুঁটি মাঝারি আকারের হলে তোলার উপযুক্ত',
            'নিয়মিত তুললে বেশি দিন ফলন পাওয়া যায়',
            'ফুল আসার সময় সেচ দিন'
        ]
    ],
    'পেপে' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(255, 152, 0, 0.85)',
        'duration' => '৯-১০ মাস',
        'stages' => [
            [
                'title' => 'মাদা তৈরি',
                'time' => 'রোপণের ১৫ দিন আগে',
                'icon' => 'fa-circle',
                'tasks' => [
                    '৬০x৬০x৬০ সেমি আকারের গর্ত করুন',
                    'প্রতি মাদায় ৮-১০ কেজি জৈব সার দিন',
                    'গর্ত ভরাট করে ১৫ দিন রেখে দিন'
                ]
            ],
            [
                'title' => 'চারা রোপণ',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    '৩৫-৪৫ দিনের সুস্থ চারা নির্বাচন করুন',
                    'মাদায় রোপণ (দূরত্ব ২-২.৫ মিটার)',
                    'বর্ষার আগে বা পরে রোপণ করুন'
                ]
            ],
            [
                'title' => 'সার প্রয়োগ',
                'time' => '৩০-২৪০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'মাসিক ইউরিয়া ৫০ গ্রাম, টিএসপি ৫০ গ্রাম, এমওপি ৫০ গ্রাম',
                    'নিয়মিত সেচ দিন (গ্রীষ্মে ৩-৪ দিন পর পর)',
                    'গাছের গোড়া আগাছামুক্ত রাখুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'পেপে রিং স্পট ভাইরাস',
                'symptoms' => 'পাতায় হলুদ রিং, ফলে দাগ',
                'prevention' => 'রোগমুক্ত চারা ব্যবহার, ভাইরাস বাহক পোকা দমন',
                'treatment' => 'আক্রান্ত গাছ তুলে পুড়িয়ে ফেলুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'মিলিবাগ',
                'symptoms' => 'কাণ্ড ও পাতায় সাদা তুলার মতো পোকা',
                'treatment' => 'Actara 25 WG (০.৩ গ্রাম/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'ফল হলুদাভ হলে তোলার উপযুক্ত',
            'পুরুষ ও স্ত্রী গাছ আলাদা করা জরুরি',
            'বর্ষায় জলাবদ্ধতা এড়িয়ে চলুন'
        ]
    ],
    'লাউ' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(139, 195, 74, 0.85)',
        'duration' => '৯০-১০০ দিন',
        'stages' => [
            [
                'title' => 'মাদা প্রস্তুতি',
                'time' => 'বপনের ১০ দিন আগে',
                'icon' => 'fa-circle',
                'tasks' => [
                    '৪৫x৪৫x৪৫ সেমি আকারের গর্ত করুন',
                    'প্রতি মাদায় ৫-৬ কেজি জৈব সার দিন',
                    'গর্ত ভরাট করে রেখে দিন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'প্রতি মাদায় ৩-৪টি বীজ বপন করুন',
                    'মাদার দূরত্ব ২-২.৫ মিটার',
                    'ফেব্রুয়ারি-মার্চ বা আগস্ট-সেপ্টেম্বর বপনের সময়'
                ]
            ],
            [
                'title' => 'মাচা ও পরিচর্যা',
                'time' => '২০-৬০ দিন',
                'icon' => 'fa-leaf',
                'tasks' => [
                    'মাচা তৈরি করুন (১.৫-২ মিটার উঁচু)',
                    'ইউরিয়া সার প্রয়োগ (প্রতি মাদায় ৫০ গ্রাম)',
                    'নিয়মিত সেচ দিন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'ডাউনি মিলডিউ',
                'symptoms' => 'পাতায় হলুদ দাগ, নিচে সাদা ছত্রাক',
                'prevention' => 'সুষম সার ব্যবহার, বায়ু চলাচল ভালো রাখুন',
                'treatment' => 'Secure 600 WG (২ গ্রাম/লিটার) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'ফল মাছি',
                'symptoms' => 'ফলে ছিদ্র, ফল পচে যায়',
                'treatment' => 'ফেরোমন ফাঁদ ব্যবহার, Cyper Plus 505 EC স্প্রে করুন'
            ]
        ],
        'tips' => [
            'ফল কোমল থাকা অবস্থায় তোলুন',
            'হাতে-পরাগায়ন করলে ফলন বাড়ে',
            'গাছপ্রতি ৩-৪টি ফল রাখুন'
        ]
    ],
    'মিষ্টি কুমড়া' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(255, 152, 0, 0.85)',
        'duration' => '১০০-১২০ দিন',
        'stages' => [
            [
                'title' => 'মাদা তৈরি',
                'time' => 'বপনের ১৫ দিন আগে',
                'icon' => 'fa-circle',
                'tasks' => [
                    '৫০x৫০x৫০ সেমি আকারের গর্ত করুন',
                    'প্রতি মাদায় ৮-১০ কেজি জৈব সার দিন',
                    'টিএসপি ৫০ গ্রাম ও এমওপি ৫০ গ্রাম মিশিয়ে দিন'
                ]
            ],
            [
                'title' => 'বীজ বপন',
                'time' => '০-৫ দিন',
                'icon' => 'fa-hand-holding-seedling',
                'tasks' => [
                    'প্রতি মাদায় ৪-৫টি বীজ বপন করুন',
                    'মাদার দূরত্ব ২.৫-৩ মিটার',
                    'মার্চ-এপ্রিল বা আগস্ট-সেপ্টেম্বর বপনের সময়'
                ]
            ],
            [
                'title' => 'সার ও পরিচর্যা',
                'time' => '২৫-৭০ দিন',
                'icon' => 'fa-spray-can',
                'tasks' => [
                    'ইউরিয়া সার প্রয়োগ (প্রতি মাদায় ৫০-৬০ গ্রাম - দুই কিস্তিতে)',
                    'নিয়মিত সেচ দিন (৭-১০ দিন পর পর)',
                    'লতা ছড়ানোর জায়গা রাখুন'
                ]
            ]
        ],
        'diseases' => [
            [
                'name' => 'পাউডারি মিলডিউ',
                'symptoms' => 'পাতায় সাদা পাউডার, পাতা শুকিয়ে যায়',
                'prevention' => 'রোগমুক্ত বীজ ব্যবহার, গাছের ঘনত্ব কম রাখুন',
                'treatment' => 'Tilt 250 EC (০.৫ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'pests' => [
            [
                'name' => 'লাল কুমড়া পোকা',
                'symptoms' => 'পাতা খেয়ে ফেলে, গাছের ক্ষতি করে',
                'treatment' => 'Ripcord 10 EC (১ মিলি/লিটার) স্প্রে করুন'
            ]
        ],
        'tips' => [
            'ফলের বোঁটা শুকিয়ে গেলে তোলার সময়',
            'ফল তোলার পর ১৫-২০ দিন রাখলে মিষ্টি বাড়ে',
            'শুষ্ক ও ছায়াযুক্ত স্থানে সংরক্ষণ করুন'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফসল পরিচর্যা গাইড - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: url('../agrologo/iot1.jpg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        main.col-md-9 {
            background: rgba(255, 255, 255, 0);
            backdrop-filter: blur(3px);
            border-radius: 20px 0 0 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        nav.navbar {
            background: rgba(40, 167, 69, 0.5) !important;
            backdrop-filter: blur(15px);
        }

        .col-md-3 {
            background: rgba(40, 167, 69, 0.3);
            backdrop-filter: blur(15px);
        }

        .page-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.85) 0%, rgba(32, 201, 151, 0.85) 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
            animation: fadeInUp 0.8s ease;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .page-header h1, .page-header p {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .icon-float {
            animation: float 3s ease-in-out infinite;
        }

        .crop-selector {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.6);
            animation: fadeInUp 0.8s ease;
        }

        .crop-select {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(40, 167, 69, 0.6);
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 1.2rem;
            color: #1a5928;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        .crop-info-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.5);
            animation: fadeInUp 0.8s ease;
            display: none;
        }

        .crop-info-card.active {
            display: block;
        }

        .crop-info-card h2 {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 20px;
            border-radius: 10px;
            display: inline-block;
            text-shadow: none;
            border: 3px solid rgba(40, 167, 69, 0.6);
        }

        .crop-info-card .lead {
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            font-weight: 800;
            color: #000000;
            border: 2px solid rgba(40, 167, 69, 0.4);
        }

        .timeline {
            position: relative;
            padding-left: 50px;
            margin-top: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, rgba(40, 167, 69, 0.8), rgba(32, 201, 151, 0.8));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
        }

        .timeline-marker {
            position: absolute;
            left: -38px;
            top: 5px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.9);
        }

        .timeline-content {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            border-left: 4px solid #28a745;
            border: 2px solid rgba(40, 167, 69, 0.5);
            border-left: 4px solid #28a745;
        }

        .timeline-title {
            font-size: 1.6rem;
            font-weight: 900;
            color: #000000;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            text-shadow: none;
            border: 2px solid #28a745;
        }

        .timeline-time {
            color: #000000;
            font-size: 1.05rem;
            margin-bottom: 1rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.9);
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #6c757d;
        }

        .task-list {
            list-style: none;
            padding: 0;
        }

        .task-item {
            padding: 12px 18px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            gap: 12px;
            font-weight: 700;
            color: #000000;
            border-left: 4px solid #28a745;
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-left: 4px solid #28a745;
        }

        .task-item i {
            color: #28a745;
            margin-top: 3px;
        }

        .section-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.75), rgba(32, 201, 151, 0.75));
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0 1.5rem 0;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .disease-card, .pest-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc3545;
            border: 2px solid rgba(220, 53, 69, 0.4);
            border-left: 4px solid #dc3545;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .disease-card p, .pest-card p {
            color: #000000;
            font-weight: 700;
            font-size: 1.05rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 0.8rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .pest-card {
            border-left-color: #fd7e14;
            border: 2px solid rgba(253, 126, 20, 0.4);
            border-left: 4px solid #fd7e14;
        }

        .disease-name, .pest-name {
            font-size: 1.5rem;
            font-weight: 900;
            color: #000000;
            margin-bottom: 1rem;
            background: rgba(255, 0, 0, 0.15);
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            text-shadow: none;
            border: 2px solid #dc3545;
        }

        .pest-name {
            color: #000000;
            background: rgba(255, 152, 0, 0.2);
            border: 2px solid #fd7e14;
        }

        .info-label {
            font-weight: 900;
            color: #000000;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            border: 2px solid #6c757d;
        }

        .tips-section {
            background: rgba(255, 248, 220, 0.85);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 3px solid rgba(255, 193, 7, 0.6);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .tips-section h4 {
            color: #000000;
            font-weight: 900;
            font-size: 1.5rem;
            background: rgba(255, 248, 220, 0.95);
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            border: 2px solid #ffc107;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0 0 0;
        }

        .tips-list li {
            padding: 12px 18px;
            background: rgba(255, 248, 220, 0.9);
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            gap: 12px;
            font-weight: 700;
            color: #000000;
            font-size: 1.05rem;
            border-left: 4px solid #ffc107;
            border: 2px solid rgba(255, 193, 7, 0.4);
            border-left: 4px solid #ffc107;
        }

        .tips-list i {
            color: #ffc107;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="page-header text-center">
                    <h1 class="display-4 fw-bold mb-2">
                        <i class="fas fa-book-open icon-float"></i> ফসল পরিচর্যা গাইড
                    </h1>
                    <p class="lead mb-0">বিস্তারিত ধাপে ধাপে ফসলের যত্ন নিন এবং ভালো ফলন পান</p>
                </div>

                <div class="alert" style="background: rgba(23, 162, 184, 0.15); border: 2px solid rgba(23, 162, 184, 0.5); border-radius: 15px;">
                    <h5 style="color: #efebea; font-weight: 700;"><i class="fas fa-info-circle"></i> কীভাবে ব্যবহার করবেন</h5>
                    <p class="mb-0" style="color: #336c0c; font-weight: 500;">নিচের তালিকা থেকে আপনার ফসল নির্বাচন করুন এবং বপন থেকে কাটা পর্যন্ত সম্পূর্ণ পরিচর্যা গাইড দেখুন।</p>
                </div>

                <div class="crop-selector">
                    <label for="cropSelect" class="form-label mb-3">
                        <h4 style="color: #1a7c31; font-weight: 800; font-size: 1.6rem; text-shadow: 0 2px 4px rgba(255, 255, 255, 0.8);"><i class="fas fa-leaf"></i> আপনার ফসল নির্বাচন করুন</h4>
                    </label>
                    <select class="form-select crop-select" id="cropSelect" onchange="showCropGuide(this.value)">
                        <option value="">-- ফসল বেছে নিন --</option>
                        <?php foreach ($crop_guides as $crop_name => $guide): ?>
                        <option value="<?php echo htmlspecialchars($crop_name); ?>">
                            <?php echo $crop_name; ?> (সময়কাল: <?php echo $guide['duration']; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php foreach ($crop_guides as $crop_name => $guide): ?>
                <div class="crop-info-card" id="crop-<?php echo htmlspecialchars($crop_name); ?>">
                    <div class="text-center mb-4">
                        <h2 style="color: <?php echo $guide['color']; ?>;">
                            <i class="fas <?php echo $guide['icon']; ?>"></i> 
                            <?php echo $crop_name; ?> চাষ পদ্ধতি
                        </h2>
                        <p class="lead">সময়কাল: <strong><?php echo $guide['duration']; ?></strong></p>
                    </div>

                    <div class="section-header">
                        <h3><i class="fas fa-tasks"></i> চাষের ধাপসমূহ</h3>
                    </div>
                    
                    <div class="timeline">
                        <?php foreach ($guide['stages'] as $index => $stage): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas <?php echo $stage['icon']; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <?php echo ($index + 1) . '. ' . $stage['title']; ?>
                                </div>
                                <div class="timeline-time">
                                    <i class="far fa-clock"></i> <?php echo $stage['time']; ?>
                                </div>
                                <ul class="task-list">
                                    <?php foreach ($stage['tasks'] as $task): ?>
                                    <li class="task-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span><?php echo $task; ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="section-header mt-5">
                        <h3><i class="fas fa-disease"></i> রোগ ব্যবস্থাপনা</h3>
                    </div>

                    <?php foreach ($guide['diseases'] as $disease): ?>
                    <div class="disease-card">
                        <div class="disease-name">
                            <i class="fas fa-virus"></i> <?php echo $disease['name']; ?>
                        </div>
                        <div class="info-label"><i class="fas fa-exclamation-triangle"></i> লক্ষণ:</div>
                        <p><?php echo $disease['symptoms']; ?></p>
                        <div class="info-label"><i class="fas fa-shield-alt"></i> প্রতিরোধ:</div>
                        <p><?php echo $disease['prevention']; ?></p>
                        <div class="info-label"><i class="fas fa-pills"></i> চিকিৎসা:</div>
                        <p><?php echo $disease['treatment']; ?></p>
                    </div>
                    <?php endforeach; ?>

                    <div class="section-header mt-5">
                        <h3><i class="fas fa-bug"></i> পোকামাকড় দমন</h3>
                    </div>

                    <?php foreach ($guide['pests'] as $pest): ?>
                    <div class="pest-card">
                        <div class="pest-name">
                            <i class="fas fa-spider"></i> <?php echo $pest['name']; ?>
                        </div>
                        <div class="info-label"><i class="fas fa-eye"></i> চেনার উপায়:</div>
                        <p><?php echo $pest['symptoms']; ?></p>
                        <div class="info-label"><i class="fas fa-spray-can"></i> দমন ব্যবস্থা:</div>
                        <p><?php echo $pest['treatment']; ?></p>
                    </div>
                    <?php endforeach; ?>

                    <div class="tips-section">
                        <h4><i class="fas fa-lightbulb"></i> গুরুত্বপূর্ণ টিপস</h4>
                        <ul class="tips-list">
                            <?php foreach ($guide['tips'] as $tip): ?>
                            <li><i class="fas fa-star"></i> <span><?php echo $tip; ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showCropGuide(cropName) {
            document.querySelectorAll('.crop-info-card').forEach(card => {
                card.classList.remove('active');
            });

            if (cropName) {
                const selectedCard = document.getElementById('crop-' + cropName);
                if (selectedCard) {
                    selectedCard.classList.add('active');
                    setTimeout(() => {
                        selectedCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        }
    </script>
</body>
</html>
