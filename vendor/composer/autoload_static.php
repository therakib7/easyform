<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita22c51766b8b1c405f998b35e0dbdf19
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Rhef\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rhef\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Rhef\\Ctrl\\Ajax\\AjaxCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Ajax/AjaxCtrl.php',
        'Rhef\\Ctrl\\Ajax\\Type\\Auth' => __DIR__ . '/../..' . '/app/Ctrl/Ajax/Type/Auth.php',
        'Rhef\\Ctrl\\Api\\ApiCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Api/ApiCtrl.php',
        'Rhef\\Ctrl\\Api\\Type\\Dashbaord' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Dashbaord.php',
        'Rhef\\Ctrl\\Api\\Type\\Email' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Email.php',
        'Rhef\\Ctrl\\Api\\Type\\File' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/File.php',
        'Rhef\\Ctrl\\Api\\Type\\Form' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Form.php',
        'Rhef\\Ctrl\\Api\\Type\\FormBuilder' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/FormBuilder.php',
        'Rhef\\Ctrl\\Api\\Type\\Media' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Media.php',
        'Rhef\\Ctrl\\Api\\Type\\Setting' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Setting.php',
        'Rhef\\Ctrl\\Api\\Type\\Taxonomy' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Taxonomy.php',
        'Rhef\\Ctrl\\Api\\Type\\Webhook' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Webhook.php',
        'Rhef\\Ctrl\\Api\\Type\\Workspace' => __DIR__ . '/../..' . '/app/Ctrl/Api/Type/Workspace.php',
        'Rhef\\Ctrl\\Asset\\AssetCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Asset/AssetCtrl.php',
        'Rhef\\Ctrl\\Assist\\AssistCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Assist/AssistCtrl.php',
        'Rhef\\Ctrl\\Assist\\Type\\Feedback' => __DIR__ . '/../..' . '/app/Ctrl/Assist/Type/Feedback.php',
        'Rhef\\Ctrl\\Assist\\Type\\Link' => __DIR__ . '/../..' . '/app/Ctrl/Assist/Type/Link.php',
        'Rhef\\Ctrl\\Cleanup\\Style' => __DIR__ . '/../..' . '/app/Ctrl/Cleanup/Style.php',
        'Rhef\\Ctrl\\Cron\\CronCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Cron/CronCtrl.php',
        'Rhef\\Ctrl\\Hook\\HookCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Hook/HookCtrl.php',
        'Rhef\\Ctrl\\Hook\\Type\\Action\\ActionCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Hook/Type/Action/ActionCtrl.php',
        'Rhef\\Ctrl\\Hook\\Type\\Action\\Role' => __DIR__ . '/../..' . '/app/Ctrl/Hook/Type/Action/Role.php',
        'Rhef\\Ctrl\\Hook\\Type\\Filter' => __DIR__ . '/../..' . '/app/Ctrl/Hook/Type/Filter.php',
        'Rhef\\Ctrl\\Install\\InstallCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Install/InstallCtrl.php',
        'Rhef\\Ctrl\\Install\\Type\\DB' => __DIR__ . '/../..' . '/app/Ctrl/Install/Type/DB.php',
        'Rhef\\Ctrl\\Install\\Type\\Page' => __DIR__ . '/../..' . '/app/Ctrl/Install/Type/Page.php',
        'Rhef\\Ctrl\\Install\\Type\\Taxonomy' => __DIR__ . '/../..' . '/app/Ctrl/Install/Type/Taxonomy.php',
        'Rhef\\Ctrl\\Install\\Type\\Update' => __DIR__ . '/../..' . '/app/Ctrl/Install/Type/Update.php',
        'Rhef\\Ctrl\\Integrate\\Form\\FormCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Integrate/Form/FormCtrl.php',
        'Rhef\\Ctrl\\Integrate\\Form\\FormList' => __DIR__ . '/../..' . '/app/Ctrl/Integrate/Form/FormList.php',
        'Rhef\\Ctrl\\Integrate\\IntegrateCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Integrate/IntegrateCtrl.php',
        'Rhef\\Ctrl\\Integrate\\Smtp\\SmtpCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Integrate/Smtp/SmtpCtrl.php',
        'Rhef\\Ctrl\\Integrate\\Smtp\\SmtpList' => __DIR__ . '/../..' . '/app/Ctrl/Integrate/Smtp/SmtpList.php',
        'Rhef\\Ctrl\\MainCtrl' => __DIR__ . '/../..' . '/app/Ctrl/MainCtrl.php',
        'Rhef\\Ctrl\\Menu\\MenuCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Menu/MenuCtrl.php',
        'Rhef\\Ctrl\\Menu\\Type\\Dashboard' => __DIR__ . '/../..' . '/app/Ctrl/Menu/Type/Dashboard.php',
        'Rhef\\Ctrl\\Menu\\Type\\Welcome' => __DIR__ . '/../..' . '/app/Ctrl/Menu/Type/Welcome.php',
        'Rhef\\Ctrl\\Meta\\MetaCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Meta/MetaCtrl.php',
        'Rhef\\Ctrl\\Meta\\User\\User' => __DIR__ . '/../..' . '/app/Ctrl/Meta/User/User.php',
        'Rhef\\Ctrl\\Taxonomy\\TaxonomyCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Taxonomy/TaxonomyCtrl.php',
        'Rhef\\Ctrl\\Template\\TemplateCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Template/TemplateCtrl.php',
        'Rhef\\Ctrl\\Widget\\Elementor\\ElementorCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Widget/Elementor/ElementorCtrl.php',
        'Rhef\\Ctrl\\Widget\\Elementor\\Widgets\\Registration' => __DIR__ . '/../..' . '/app/Ctrl/Widget/Elementor/Widgets/Registration.php',
        'Rhef\\Ctrl\\Widget\\Gutenberg\\GutenbergCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Widget/Gutenberg/GutenbergCtrl.php',
        'Rhef\\Ctrl\\Widget\\WidgetCtrl' => __DIR__ . '/../..' . '/app/Ctrl/Widget/WidgetCtrl.php',
        'Rhef\\Helper\\Fns' => __DIR__ . '/../..' . '/app/Helper/Fns.php',
        'Rhef\\Helper\\I18n' => __DIR__ . '/../..' . '/app/Helper/I18n.php',
        'Rhef\\Helper\\Info' => __DIR__ . '/../..' . '/app/Helper/Info.php',
        'Rhef\\Helper\\Preset' => __DIR__ . '/../..' . '/app/Helper/Preset.php',
        'Rhef\\Model\\FormBuilder' => __DIR__ . '/../..' . '/app/Model/FormBuilder.php',
        'Rhef\\Model\\Project' => __DIR__ . '/../..' . '/app/Model/Project.php',
        'Rhef\\Traits\\Singleton' => __DIR__ . '/../..' . '/app/Traits/Singleton.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita22c51766b8b1c405f998b35e0dbdf19::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita22c51766b8b1c405f998b35e0dbdf19::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita22c51766b8b1c405f998b35e0dbdf19::$classMap;

        }, null, ClassLoader::class);
    }
}