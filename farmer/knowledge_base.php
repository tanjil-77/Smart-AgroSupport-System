<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Knowledge base categories with articles
$knowledge_base = [
    'মাটি ব্যবস্থাপনা' => [
        'icon' => 'fa-layer-group',
        'color' => 'rgba(139, 69, 19, 0.85)',
        'articles' => [
            ['title' => 'মাটি পরীক্ষার পদ্ধতি', 'desc' => 'মাটির উর্বরতা পরীক্ষা করে সঠিক সার নির্ধারণ করুন'],
            ['title' => 'জৈব সার ব্যবহার', 'desc' => 'রাসায়নিক সারের বিকল্প হিসেবে জৈব সার ব্যবহার করুন'],
            ['title' => 'মাটির অম্লত্ব নিয়ন্ত্রণ', 'desc' => 'pH মাত্রা সঠিক রেখে ফসলের উৎপাদন বৃদ্ধি করুন'],
            ['title' => 'মাটি ক্ষয় রোধ', 'desc' => 'ভূমিক্ষয় রোধে সঠিক ব্যবস্থাপনা গ্রহণ করুন'],
        ]
    ],
    'পোকামাকড় দমন' => [
        'icon' => 'fa-bug',
        'color' => 'rgba(220, 53, 69, 0.85)',
        'articles' => [
            ['title' => 'জৈব কীটনাশক', 'desc' => 'পরিবেশবান্ধব কীটনাশক ব্যবহার করে ফসল রক্ষা করুন'],
            ['title' => 'সমন্বিত বালাই ব্যবস্থাপনা', 'desc' => 'IPM পদ্ধতিতে পোকামাকড় নিয়ন্ত্রণ করুন'],
            ['title' => 'উপকারী পোকা চিনুন', 'desc' => 'উপকারী ও ক্ষতিকর পোকা শনাক্ত করুন'],
            ['title' => 'ফসল পর্যবেক্ষণ', 'desc' => 'নিয়মিত ফসল পর্যবেক্ষণ করে সমস্যা দ্রুত চিহ্নিত করুন'],
        ]
    ],
    'সেচ ব্যবস্থাপনা' => [
        'icon' => 'fa-tint',
        'color' => 'rgba(0, 123, 255, 0.85)',
        'articles' => [
            ['title' => 'ড্রিপ সেচ পদ্ধতি', 'desc' => 'পানি সাosশয়ী সেচ পদ্ধতি ব্যবহার করুন'],
            ['title' => 'বৃষ্টির পানি সংরক্ষণ', 'desc' => 'বর্ষাকালে পানি সংরক্ষণ করে সেচে ব্যবহার করুন'],
            ['title' => 'সেচ সময়সূচী', 'desc' => 'ফসলভেদে সঠিক সময়ে সেচ দিন'],
            ['title' => 'ভূগর্ভস্থ পানি ব্যবস্থাপনা', 'desc' => 'ভূগর্ভস্থ পানির সঠিক ব্যবহার নিশ্চিত করুন'],
        ]
    ],
    'বীজ সংরক্ষণ' => [
        'icon' => 'fa-seedling',
        'color' => 'rgba(40, 167, 69, 0.85)',
        'articles' => [
            ['title' => 'মানসম্পন্ন বীজ নির্বাচন', 'desc' => 'ভালো ফলনের জন্য উন্নত বীজ বাছাই করুন'],
            ['title' => 'বীজ শোধন পদ্ধতি', 'desc' => 'রোগমুক্ত বীজের জন্য শোধন করুন'],
            ['title' => 'বীজ সংরক্ষণ কৌশল', 'desc' => 'সঠিক তাপমাত্রা ও আর্দ্রতায় বীজ সংরক্ষণ করুন'],
            ['title' => 'বীজের অঙ্কুরোদগম পরীক্ষা', 'desc' => 'বপনের আগে বীজের গুণগত মান যাচাই করুন'],
        ]
    ],
    'রোগ ব্যবস্থাপনা' => [
        'icon' => 'fa-shield-virus',
        'color' => 'rgba(255, 193, 7, 0.85)',
        'articles' => [
            ['title' => 'ছত্রাকজনিত রোগ', 'desc' => 'ছত্রাক আক্রমণ প্রতিরোধ ও প্রতিকার করুন'],
            ['title' => 'ভাইরাস রোগ নিয়ন্ত্রণ', 'desc' => 'ভাইরাস বাহক পোকা দমন করুন'],
            ['title' => 'ব্যাকটেরিয়াজনিত রোগ', 'desc' => 'ব্যাকটেরিয়া সংক্রমণ থেকে ফসল রক্ষা করুন'],
            ['title' => 'রোগ প্রতিরোধী জাত', 'desc' => 'রোগ সহনশীল ফসলের জাত ব্যবহার করুন'],
        ]
    ],
    'জৈব চাষাবাদ' => [
        'icon' => 'fa-leaf',
        'color' => 'rgba(76, 175, 80, 0.85)',
        'articles' => [
            ['title' => 'জৈব সার তৈরি', 'desc' => 'কম্পোস্ট ও ভার্মি কম্পোস্ট তৈরি করুন'],
            ['title' => 'জৈব কীটনাশক', 'desc' => 'প্রাকৃতিক উপাদান দিয়ে কীটনাশক তৈরি করুন'],
            ['title' => 'ফসল চক্র', 'desc' => 'মাটির উর্বরতা বৃদ্ধিতে ফসল চক্র অনুসরণ করুন'],
            ['title' => 'সবুজ সার', 'desc' => 'সবুজ সার ফসল চাষ করে মাটির উন্নতি করুন'],
        ]
    ],
    'আধুনিক প্রযুক্তি' => [
        'icon' => 'fa-robot',
        'color' => 'rgba(103, 58, 183, 0.85)',
        'articles' => [
            ['title' => 'স্মার্ট কৃষি', 'desc' => 'IoT ও সেন্সর ব্যবহার করে স্মার্ট চাষাবাদ করুন'],
            ['title' => 'ড্রোন ব্যবহার', 'desc' => 'ফসল পর্যবেক্ষণ ও কীটনাশক প্রয়োগে ড্রোন ব্যবহার করুন'],
            ['title' => 'আবহাওয়া পূর্বাভাস', 'desc' => 'মোবাইল অ্যাপ দিয়ে আবহাওয়া তথ্য জানুন'],
            ['title' => 'কৃষি যন্ত্রপাতি', 'desc' => 'আধুনিক যন্ত্রপাতি ব্যবহার করে খরচ কমান'],
        ]
    ],
    'বাজার ও বিপণন' => [
        'icon' => 'fa-store',
        'color' => 'rgba(255, 152, 0, 0.85)',
        'articles' => [
            ['title' => 'ফসল বিক্রয় কৌশল', 'desc' => 'সঠিক সময়ে সঠিক বাজারে ফসল বিক্রয় করুন'],
            ['title' => 'মূল্য নির্ধারণ', 'desc' => 'বাজার দর বুঝে ন্যায্য মূল্য পান'],
            ['title' => 'অনলাইন বিপণন', 'desc' => 'ডিজিটাল প্ল্যাটফর্মে ফসল বিক্রয় করুন'],
            ['title' => 'মান নিয়ন্ত্রণ', 'desc' => 'উন্নত মানের ফসল উৎপাদন করে বেশি দাম পান'],
        ]
    ],
    'ফসল সংগ্রহ ও সংরক্ষণ' => [
        'icon' => 'fa-warehouse',
        'color' => 'rgba(233, 30, 99, 0.85)',
        'articles' => [
            ['title' => 'সঠিক সময়ে ফসল সংগ্রহ', 'desc' => 'পরিপক্বতা বুঝে ফসল কাটুন এবং ক্ষতি কমান'],
            ['title' => 'ফসল শুকানো পদ্ধতি', 'desc' => 'রোদে বা যন্ত্রে শুকিয়ে আর্দ্রতা কমান'],
            ['title' => 'সংরক্ষণ কৌশল', 'desc' => 'শীতল ও শুষ্ক স্থানে ফসল সংরক্ষণ করুন'],
            ['title' => 'পোকামাকড় থেকে রক্ষা', 'desc' => 'সংরক্ষিত ফসল পোকার আক্রমণ থেকে রক্ষা করুন'],
            ['title' => 'প্যাকেজিং পদ্ধতি', 'desc' => 'সঠিক প্যাকেজিং করে দীর্ঘ সময় সংরক্ষণ করুন'],
        ]
    ],
    'জলবায়ু পরিবর্তন মোকাবেলা' => [
        'icon' => 'fa-temperature-high',
        'color' => 'rgba(156, 39, 176, 0.85)',
        'articles' => [
            ['title' => 'খরা সহনশীল ফসল', 'desc' => 'জলবায়ু পরিবর্তনে উপযোগী ফসল চাষ করুন'],
            ['title' => 'বন্যা প্রতিরোধী চাষ', 'desc' => 'উঁচু জমিতে বা ভাসমান বেডে চাষাবাদ করুন'],
            ['title' => 'কার্বন সংরক্ষণ', 'desc' => 'মাটিতে জৈব পদার্থ যোগ করে কার্বন ধরে রাখুন'],
            ['title' => 'পানি সংরক্ষণ কৌশল', 'desc' => 'বৃষ্টির পানি সংরক্ষণ করে খরায় ব্যবহার করুন'],
        ]
    ],
    'পশুপাখি পালন' => [
        'icon' => 'fa-cow',
        'color' => 'rgba(63, 81, 181, 0.85)',
        'articles' => [
            ['title' => 'গবাদি পশু পালন', 'desc' => 'গরু, ছাগল, ভেড়া পালনে সঠিক পরিচর্যা করুন'],
            ['title' => 'হাঁস-মুরগি পালন', 'desc' => 'পোল্ট্রি ফার্মিং করে আয় বাড়ান'],
            ['title' => 'টিকা প্রদান', 'desc' => 'সময়মত টিকা দিয়ে রোগ প্রতিরোধ করুন'],
            ['title' => 'খাদ্য ব্যবস্থাপনা', 'desc' => 'পুষ্টিকর খাবার দিয়ে পশু স্বাস্থ্যবান রাখুন'],
            ['title' => 'দুধ ও মাংস উৎপাদন', 'desc' => 'সঠিক পরিচর্যায় উৎপাদন বৃদ্ধি করুন'],
        ]
    ],
    'মৎস্য চাষ' => [
        'icon' => 'fa-fish',
        'color' => 'rgba(0, 188, 212, 0.85)',
        'articles' => [
            ['title' => 'পুকুর তৈরি', 'desc' => 'সঠিক গভীরতা ও আয়তনের পুকুর তৈরি করুন'],
            ['title' => 'মাছের পোনা নির্বাচন', 'desc' => 'উন্নত জাতের পোনা সংগ্রহ করুন'],
            ['title' => 'খাদ্য প্রয়োগ', 'desc' => 'নিয়মিত সঠিক পরিমাণে খাবার দিন'],
            ['title' => 'পানির গুণমান', 'desc' => 'পানির pH ও অক্সিজেন নিয়ন্ত্রণ করুন'],
            ['title' => 'রোগ প্রতিরোধ', 'desc' => 'মাছের রোগ দ্রুত শনাক্ত করে চিকিৎসা করুন'],
        ]
    ],
    'ফল চাষ' => [
        'icon' => 'fa-apple-alt',
        'color' => 'rgba(244, 67, 54, 0.85)',
        'articles' => [
            ['title' => 'বাগান পরিকল্পনা', 'desc' => 'সঠিক দূরত্ব বজায় রেখে ফলের চারা রোপণ করুন'],
            ['title' => 'ডাল ছাঁটাই', 'desc' => 'নিয়মিত ডাল ছাঁটাই করে ফলন বৃদ্ধি করুন'],
            ['title' => 'সার প্রয়োগ', 'desc' => 'জৈব ও রাসায়নিক সার সঠিক সময়ে দিন'],
            ['title' => 'ফল পাতলাকরণ', 'desc' => 'অতিরিক্ত ফল পাতলা করে বড় ফল পান'],
            ['title' => 'রোগ ও পোকা দমন', 'desc' => 'ফলের গাছ রোগমুক্ত রাখুন'],
        ]
    ],
    'সবজি চাষ' => [
        'icon' => 'fa-carrot',
        'color' => 'rgba(255, 87, 34, 0.85)',
        'articles' => [
            ['title' => 'মৌসুমি সবজি', 'desc' => 'প্রতি মৌসুমে উপযুক্ত সবজি চাষ করুন'],
            ['title' => 'হাইব্রিড জাত', 'desc' => 'উচ্চ ফলনশীল হাইব্রিড সবজির বীজ ব্যবহার করুন'],
            ['title' => 'পলিব্যাগে চাষ', 'desc' => 'ছাদে বা সীমিত জায়গায় পলিব্যাগে চাষ করুন'],
            ['title' => 'শীতকালীন সবজি', 'desc' => 'শীতে টমেটো, ফুলকপি, বাঁধাকপি চাষ করুন'],
            ['title' => 'গ্রীষ্মকালীন সবজি', 'desc' => 'গ্রীষ্মে লাউ, কুমড়া, ঢেঁড়স চাষ করুন'],
        ]
    ],
    'ভার্মিকম্পোস্ট উৎপাদন' => [
        'icon' => 'fa-recycle',
        'color' => 'rgba(139, 195, 74, 0.85)',
        'articles' => [
            ['title' => 'কেঁচো নির্বাচন', 'desc' => 'উপযুক্ত প্রজাতির কেঁচো সংগ্রহ করুন'],
            ['title' => 'বেড তৈরি', 'desc' => 'ছায়াযুক্ত স্থানে কম্পোস্ট বেড তৈরি করুন'],
            ['title' => 'খাদ্য প্রস্তুতি', 'desc' => 'জৈব বর্জ্য পচিয়ে কেঁচোর খাবার তৈরি করুন'],
            ['title' => 'আর্দ্রতা নিয়ন্ত্রণ', 'desc' => 'বেড সবসময় আর্দ্র রাখুন কিন্তু পানিবদ্ধ নয়'],
            ['title' => 'সংগ্রহ ও সংরক্ষণ', 'desc' => 'প্রস্তুত কম্পোস্ট সংগ্রহ করে শুকনো স্থানে রাখুন'],
        ]
    ],
    'মশলা ফসল চাষ' => [
        'icon' => 'fa-pepper-hot',
        'color' => 'rgba(244, 109, 33, 0.85)',
        'articles' => [
            ['title' => 'মরিচ চাষ পদ্ধতি', 'desc' => 'গরম ও শুষ্ক আবহাওয়ায় মরিচ চাষ করুন'],
            ['title' => 'হলুদ চাষ', 'desc' => 'বছরব্যাপী চাষ উপযোগী হলুদের জাত ব্যবহার করুন'],
            ['title' => 'পেঁয়াজ ও রসুন চাষ', 'desc' => 'শীতকালে পেঁয়াজ ও রসুন চাষের সঠিক পদ্ধতি'],
            ['title' => 'মেথি চাষ', 'desc' => 'শীতে মেথি সাশ্রয়ী মূল্যে চাষ করুন'],
            ['title' => 'ধনিয়া ও জিরা চাষ', 'desc' => 'সুগন্ধি মশলা চাষে আয় বৃদ্ধি করুন'],
        ]
    ],
    'চা ও কফি চাষ' => [
        'icon' => 'fa-mug-hot',
        'color' => 'rgba(78, 55, 36, 0.85)',
        'articles' => [
            ['title' => 'চা গাছ রোপণ', 'desc' => 'উঁচু ও ঢালু জমিতে চা বাগান স্থাপন করুন'],
            ['title' => 'পাতা সংগ্রহ', 'desc' => 'সঠিক সময়ে ও পদ্ধতিতে চা পাতা সংগ্রহ করুন'],
            ['title' => 'চা প্রক্রিয়াকরণ', 'desc' => 'শুকিয়ে ও গাঁজিয়ে চা তৈরি করুন'],
            ['title' => 'কফি চাষ', 'desc' => 'উপযুক্ত জলবায়ুতে কফি চাষ করুন'],
            ['title' => 'সার ব্যবস্থাপনা', 'desc' => 'চা বাগানে সঠিক মাত্রায় সার প্রয়োগ করুন'],
        ]
    ],
    'ফুল চাষ' => [
        'icon' => 'fa-flower',
        'color' => 'rgba(233, 64, 87, 0.85)',
        'articles' => [
            ['title' => 'ফুলের বাগান স্থাপনা', 'desc' => 'লাভজনক ফুল চাষের জন্য বাগান পরিকল্পনা করুন'],
            ['title' => 'সিজনী ফুল', 'desc' => 'প্রতি মৌসুমে চাহিদাসম্পন্ন ফুল চাষ করুন'],
            ['title' => 'গোলাপ চাষ', 'desc' => 'বছরব্যাপী গোলাপ চাষে উচ্চ আয় করুন'],
            ['title' => 'ফুলের পরিচর্যা', 'desc' => 'ফুলের গুণমান রক্ষা করে ফলন বাড়ান'],
            ['title' => 'ফুল সংরক্ষণ', 'desc' => 'তাজা ফুল দীর্ঘ সময় সংরক্ষণ করুন'],
        ]
    ],
    'মধু চাষ' => [
        'icon' => 'fa-bee',
        'color' => 'rgba(255, 193, 7, 0.85)',
        'articles' => [
            ['title' => 'মৌমাছি নির্বাচন', 'desc' => 'মধু উৎপাদনে সেরা প্রজাতির মৌমাছি ব্যবহার করুন'],
            ['title' => 'চাকে রক্ষণাবেক্ষণ', 'desc' => 'মৌমাছির চাক নিয়মিত পরিচর্যা করুন'],
            ['title' => 'মধু সংগ্রহ পদ্ধতি', 'desc' => 'সঠিক সময়ে ও উপায়ে মধু সংগ্রহ করুন'],
            ['title' => 'মৌমাছির রোগ', 'desc' => 'মৌমাছির রোগ প্রতিরোধ ও চিকিৎসা করুন'],
            ['title' => 'ফুলের নির্বাচন', 'desc' => 'মধু উৎপাদনে উপযোগী ফুল চাষ করুন'],
        ]
    ],
    'রেশম চাষ' => [
        'icon' => 'fa-spider',
        'color' => 'rgba(123, 85, 83, 0.85)',
        'articles' => [
            ['title' => 'রেশম পোকার যত্ন', 'desc' => 'রেশম পোকার সুস্বাস্থ্য রক্ষা করুন'],
            ['title' => 'মালবেরি চাষ', 'desc' => 'রেশম পোকার খাবার তৈরিতে মালবেরি বৃক্ষরোপণ করুন'],
            ['title' => 'রেশম কোকুন পরিচালনা', 'desc' => 'কোকুন থেকে রেশম সংগ্রহের সঠিক পদ্ধতি'],
            ['title' => 'রেশম উৎপাদন', 'desc' => 'উচ্চ মানের রেশম তৎপাদনে দক্ষতা অর্জন করুন'],
            ['title' => 'বাজার ও মূল্য', 'desc' => 'রেশম পণ্যের সঠিক বাজার খুঁজে নিন'],
        ]
    ],
    'নারকেল ও দেশীয় ফসল' => [
        'icon' => 'fa-leaf',
        'color' => 'rgba(101, 168, 68, 0.85)',
        'articles' => [
            ['title' => 'নারকেল বাগান', 'desc' => 'দীর্ঘমেয়াদী আয়ের জন্য নারকেল চাষ করুন'],
            ['title' => 'নাড়িকেল সংগ্রহ', 'desc' => 'পরিপক্বতা বুঝে সঠিক সময়ে নারকেল সংগ্রহ করুন'],
            ['title' => 'সুপারি চাষ', 'desc' => 'নারকেলের সাথে সুপারি গাছ রোপণ করুন'],
            ['title' => 'দেশীয় জাত সংরক্ষণ', 'desc' => 'ঐতিহ্যবাহী দেশীয় ফসলের চাষ চালিয়ে যান'],
            ['title' => 'পণ্য বৈচিত্র্য', 'desc' => 'নারকেল থেকে বিভিন্ন পণ্য তৈরি করুন'],
        ]
    ],
    'কৃষি প্রশিক্ষণ ও উন্নয়ন' => [
        'icon' => 'fa-graduation-cap',
        'color' => 'rgba(33, 150, 243, 0.85)',
        'articles' => [
            ['title' => 'কৃষক প্রশিক্ষণ কর্মসূচি', 'desc' => 'নিয়মিত কৃষি প্রশিক্ষণ গ্রহণ করুন'],
            ['title' => 'নতুন প্রযুক্তি শিক্ষা', 'desc' => 'আধুনিক কৃষি প্রযুক্তি শিখে প্রয়োগ করুন'],
            ['title' => 'সহযোগিতা গোষ্ঠী গঠন', 'desc' => 'অন্য কৃষকদের সাথে সহযোগিতা করে ব্যবসা বাড়ান'],
            ['title' => 'সরকারি সহায়তা প্রকল্প', 'desc' => 'বিভিন্ন সরকারি কৃষি প্রকল্প থেকে লাভবান হন'],
            ['title' => 'বিশেষজ্ঞ পরামর্শ পরিষেবা', 'desc' => 'কৃষি বিশেষজ্ঞদের সাথে যোগাযোগ রাখুন'],
        ]
    ],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>জ্ঞান ভান্ডার - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background Image Styling */
        body {
            background: url('../agrologo/iot.png');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-7px);
            }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }
            50% {
                box-shadow: 0 10px 40px rgba(40, 167, 69, 0.4);
            }
        }

        /* Main container */
        main.col-md-9 {
            background: rgba(255, 255, 255, 0);
            backdrop-filter: blur(3px);
            border-radius: 20px 0 0 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Navbar styling */
        nav.navbar {
            background: rgba(40, 167, 69, 0.5) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Sidebar styling */
        .col-md-3 {
            background: rgba(40, 167, 69, 0.3);
            backdrop-filter: blur(15px);
        }

        .page-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.65) 0%, rgba(32, 201, 151, 0.65) 100%);
            color: white;
            padding: 2rem 0;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
            animation: fadeInUp 0.6s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .page-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        .icon-float {
            animation: float 3s ease-in-out infinite;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.4);
            animation: slideIn 0.6s ease;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: #28a745;
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
            outline: none;
        }

        .category-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 2px solid rgba(40, 167, 69, 0.5);
            height: 100%;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
            backdrop-filter: blur(5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .category-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(40, 167, 69, 0.4);
            border-color: #28a745;
            background: rgba(255, 255, 255, 0.12);
        }

        .category-header {
            color: white;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            border-bottom: 3px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .category-header:hover {
            transform: scale(1.02);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            animation: bounce 1s infinite;
            transform: scale(1.1);
        }

        .category-body {
            padding: 1.5rem;
            display: none;
            background: rgba(255, 255, 255, 0.85);
        }

        .category-body.show {
            display: block;
        }

        .article-item {
            background: rgba(40, 167, 69, 0.7);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            border-left: 4px solid #20c997;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .article-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(40, 167, 69, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .article-item:hover::before {
            left: 100%;
        }

        .article-item:hover {
            background: rgba(40, 167, 69, 0.85);
            transform: translateX(10px) scale(1.02);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
            border-left-width: 6px;
        }

        .article-title {
            font-weight: 700;
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .article-desc {
            color: #ffffff;
            font-size: 0.95rem;
            margin: 0;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        /* Stagger animation for cards */
        .category-card:nth-child(1) { animation-delay: 0.1s; }
        .category-card:nth-child(2) { animation-delay: 0.2s; }
        .category-card:nth-child(3) { animation-delay: 0.3s; }
        .category-card:nth-child(4) { animation-delay: 0.4s; }
        .category-card:nth-child(5) { animation-delay: 0.5s; }
        .category-card:nth-child(6) { animation-delay: 0.6s; }
        .category-card:nth-child(7) { animation-delay: 0.7s; }
        .category-card:nth-child(8) { animation-delay: 0.8s; }
        .category-card:nth-child(9) { animation-delay: 0.9s; }
        .category-card:nth-child(10) { animation-delay: 1.0s; }
        .category-card:nth-child(11) { animation-delay: 1.1s; }
        .category-card:nth-child(12) { animation-delay: 1.2s; }
        .category-card:nth-child(13) { animation-delay: 1.3s; }
        .category-card:nth-child(14) { animation-delay: 1.4s; }
        .category-card:nth-child(15) { animation-delay: 1.5s; }
        .category-card:nth-child(16) { animation-delay: 1.6s; }
        .category-card:nth-child(17) { animation-delay: 1.7s; }
        .category-card:nth-child(18) { animation-delay: 1.8s; }
        .category-card:nth-child(19) { animation-delay: 1.9s; }
        .category-card:nth-child(20) { animation-delay: 2.0s; }
        .category-card:nth-child(21) { animation-delay: 2.1s; }
        .category-card:nth-child(22) { animation-delay: 2.2s; }
        .category-card:nth-child(23) { animation-delay: 2.3s; }
        .category-card:nth-child(24) { animation-delay: 2.4s; }

        /* Permanent visibility rules for info boxes - 2x2 layout */
        .info-boxes { 
            display: flex !important; 
            visibility: visible !important; 
            opacity: 1 !important; 
            flex-wrap: wrap !important;
            width: 100% !important;
            gap: 1.5rem !important;
        }
        .info-box-wrapper { 
            display: block !important; 
            visibility: visible !important; 
            opacity: 1 !important; 
            flex: 0 0 calc(50% - 0.75rem) !important;
            max-width: calc(50% - 0.75rem) !important;
        }
        .info-boxes .info-box { 
            display: block !important; 
            visibility: visible !important; 
            opacity: 1 !important; 
        }
        
        /* Responsive - single column on smaller screens */
        @media (max-width: 991px) {
            .info-box-wrapper {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Page Header -->
                <div class="page-header text-center">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-book-reader icon-float"></i> জ্ঞান ভান্ডার
                    </h1>
                    <p class="lead mb-0">কৃষি সম্পর্কিত গুরুত্বপূর্ণ তথ্য ও টিপস</p>
                </div>

                <!-- Search Box -->
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text" style="background: transparent; border: none;">
                            <i class="fas fa-search text-success"></i>
                        </span>
                        <input type="text" class="form-control search-input" id="searchInput" 
                               placeholder="বিষয় অনুসন্ধান করুন..." onkeyup="searchArticles()">
                    </div>
                </div>

                <!-- Category Cards -->
                <div class="row" id="knowledgeContainer">
                    <?php foreach ($knowledge_base as $category => $data): ?>
                    <div class="col-md-6 col-lg-6 category-item">
                        <div class="category-card">
                            <div class="category-header" style="background: <?php echo $data['color']; ?>;" 
                                 onclick="toggleCategory(this)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="category-icon">
                                            <i class="fas <?php echo $data['icon']; ?>"></i>
                                        </div>
                                        <h4 class="mb-0">
                                            <?php echo $category; ?>
                                        </h4>
                                        <small><?php echo count($data['articles']); ?> টি নিবন্ধ</small>
                                    </div>
                                    <div>
                                        <i class="fas fa-chevron-down expand-icon" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="category-body">
                                <?php foreach ($data['articles'] as $article): ?>
                                <div class="article-item">
                                    <div class="article-title">
                                        <i class="fas fa-file-alt"></i>
                                        <?php echo $article['title']; ?>
                                    </div>
                                    <p class="article-desc"><?php echo $article['desc']; ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Info Section with 4 Boxes - Always Visible in 2x2 Layout -->
                <div class="row mt-5 mb-5 info-boxes" style="display: flex !important; visibility: visible !important; opacity: 1 !important; flex-wrap: wrap !important;">
                    <div class="col-lg-6 mb-4 info-box-wrapper" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <div class="info-box alert alert-success" style="display: block !important; visibility: visible !important; opacity: 1 !important; border-radius: 15px; animation: fadeInUp 0.6s ease; animation-delay: 0.2s; animation-fill-mode: both; background: linear-gradient(135deg, rgba(40, 167, 69, 0.4) 0%, rgba(40, 167, 69, 0.25) 100%); backdrop-filter: blur(15px); border: 2px solid rgba(40, 167, 69, 0.4); box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2); padding: 1.5rem;">
                            <h5 class="text-success fw-bold"><i class="fas fa-lightbulb"></i> দৈনিক টিপস</h5>
                            <ul class="mb-0">
                                <li>নিয়মিত জ্ঞান ভান্ডার থেকে নতুন তথ্য জানুন</li>
                                <li>কৃষি বিশেষজ্ঞের পরামর্শ গ্রহণ করুন</li>
                                <li>আধুনিক প্রযুক্তি ব্যবহার করে উৎপাদন বাড়ান</li>
                                <li>অন্যান্য কৃষকদের সাথে অভিজ্ঞতা শেয়ার করুন</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4 info-box-wrapper" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <div class="info-box alert alert-info" style="display: block !important; visibility: visible !important; opacity: 1 !important; border-radius: 15px; animation: fadeInUp 0.6s ease; animation-delay: 0.4s; animation-fill-mode: both; background: linear-gradient(135deg, rgba(23, 162, 184, 0.4) 0%, rgba(23, 162, 184, 0.25) 100%); backdrop-filter: blur(15px); border: 2px solid rgba(23, 162, 184, 0.4); box-shadow: 0 10px 30px rgba(23, 162, 184, 0.2); padding: 1.5rem;">
                            <h5 class="text-info fw-bold"><i class="fas fa-phone-alt"></i> সহায়তা যোগাযোগ</h5>
                            <ul class="mb-0">
                                <li><strong>কৃষি তথ্য সেবা:</strong> ১৬১২৩</li>
                                <li><strong>জরুরি হটলাইন:</strong> ০১৭১১-১১১১১১</li>
                                <li><strong>কৃষি বিভাগ:</strong> স্থানীয় অফিসে যোগাযোগ করুন</li>
                                <li><strong>অনলাইন সহায়তা:</strong> ২৪/৭ উপলব্ধ</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4 info-box-wrapper" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <div class="info-box alert alert-warning" style="display: block !important; visibility: visible !important; opacity: 1 !important; border-radius: 15px; animation: fadeInUp 0.6s ease; animation-delay: 0.6s; animation-fill-mode: both; background: linear-gradient(135deg, rgba(255, 193, 7, 0.4) 0%, rgba(255, 193, 7, 0.25) 100%); backdrop-filter: blur(15px); border: 2px solid rgba(255, 193, 7, 0.4); box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2); padding: 1.5rem;">
                            <h5 class="text-warning fw-bold"><i class="fas fa-exclamation-triangle"></i> সতর্কতা</h5>
                            <ul class="mb-0">
                                <li>অতিরিক্ত রাসায়নিক সার ব্যবহার করবেন না</li>
                                <li>মেয়াদ উত্তীর্ণ কীটনাশক ব্যবহার করবেন না</li>
                                <li>ফসলের পর পুনরায় একই জায়গায় একই ফসল লাগাবেন না</li>
                                <li>আবহাওয়ার পূর্বাভাস মেনে চলুন</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4 info-box-wrapper" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <div class="info-box alert alert-primary" style="display: block !important; visibility: visible !important; opacity: 1 !important; border-radius: 15px; animation: fadeInUp 0.6s ease; animation-delay: 0.8s; animation-fill-mode: both; background: linear-gradient(135deg, rgba(13, 110, 253, 0.4) 0%, rgba(13, 110, 253, 0.25) 100%); backdrop-filter: blur(15px); border: 2px solid rgba(13, 110, 253, 0.4); box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2); padding: 1.5rem;">
                            <h5 class="text-primary fw-bold"><i class="fas fa-book-open"></i> শিক্ষামূলক সংস্থান</h5>
                            <ul class="mb-0">
                                <li><strong>অনলাইন কোর্স:</strong> কৃষি প্রশিক্ষণ নিন</li>
                                <li><strong>ভিডিও টিউটোরিয়াল:</strong> ইউটিউবে দেখুন</li>
                                <li><strong>কৃষি মেলা:</strong> নিয়মিত অংশগ্রহণ করুন</li>
                                <li><strong>সরকারি প্রকল্প:</strong> সুবিধা নিয়ে নিন</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        function toggleCategory(header) {
            const body = header.nextElementSibling;
            const icon = header.querySelector('.expand-icon');
            
            body.classList.toggle('show');
            icon.classList.toggle('rotated');
        }

        function searchArticles() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryItems = document.querySelectorAll('.category-item');
            
            categoryItems.forEach(item => {
                const categoryText = item.textContent.toLowerCase();
                item.style.display = categoryText.includes(searchTerm) ? 'block' : 'none';
            });
        }
        
        // Scroll to top button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                if (!document.querySelector('.scroll-to-top')) {
                    const scrollBtn = document.createElement('button');
                    scrollBtn.className = 'scroll-to-top btn btn-success rounded-circle';
                    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                    scrollBtn.style.cssText = 'position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; z-index: 1000; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);';
                    scrollBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
                    document.body.appendChild(scrollBtn);
                }
            } else {
                const btn = document.querySelector('.scroll-to-top');
                if (btn) btn.remove();
            }
        });
    </script>
</body>
</html>
