Общая информация
=======================
Для миграций шаблонов бизнес процессов реализован модуль qsoft.bizproc_migration.
Модуль позволяет выгружать шаблон БП в виде массива в PHP файле.
Это позволяет работать с шаблоном как с массивом и вести его версионность с возможностью видеть кто какие вносил правки.

Цели данного процесса:

* обеспечить версионность шаблонов БП
* обеспечить корректность версий БП при накате и откате миграций
* создать возможность работать с одним БП нескольким разработчикам одновременно

Процесс экспорта в файл
=======================
```
use Bitrix\Main\Loader;  
use QSOFT\BizprocMigration\BizprocTemplate;  
use QSOFT\BizprocMigration\File\FileGenerator;  
use Bitrix\Main\IO\File;

Loader::includeModule('qsoft.bizproc_migration');

/* первый аргумент - ID шаблона БП, второй - название выгружаемого файла */  
$template = BizprocTemplate::initFromTemplateId(729, 'bp_mobileArm');

try {  
    (new FileGenerator($template))->generatePhpFile();  
} catch (Throwable $e) {  
    var_dump($e->getMessage());  
}
```

Шаблон БП будет выгружен в директорию /local/bizproc/.
При необходимости путь можно скорректировать на странице настроек модуля во вкладке "Общие настройки". 

Также имеется возможность выгрузки файла шаблона через административный интерфейс.
Для этого нужно перейти на страницу настроек модуля.
Во вкладке "Выгрузить шаблон БП" нужно ввести id шаблона и нажать на кнопку "Скачать".

Миграции БП через класс
========================

Вариант миграции через класс БП используется для того, чтобы вынести из файла миграции лишнюю обработку, не засорять код и убрать дублирование одинаковых методов.

Для миграции создаётся папка для файлов.

В папке размещаем:

* файл старого шаблона БП (если это не новый БП)
* файл нового шаблона БП
* класс БП, отнаследованный от QSOFT\BizprocMigration\BizprocTemplate

В классе БП реализуется метод checkActivity() который применяется к каждому активити в БП перед его установкой.
Метод используется для установки значений, который могут отличаться на разных площадках, такие как id групп, шаблонов документов, шаблонов БП и т.д.

В миграции подключается класс БП, через него вызывается метод initFromFile.

Пример класса шаблона
=====================

```
use QSOFT\BizprocMigration\BizprocTemplate;
use QSOFT\BizprocMigration\Variables\CrmDocumentTemplateVariable;
use QSOFT\BizprocMigration\Variables\GroupVariable;

class BPCancelAssignmentStatusEight extends BizprocTemplate
{
    /**
     * @param array $activity
     * @return void
     * @throws Exception
     */
    public static function checkActivity(array &$activity): void
    {
        switch ($activity['Name']) {
            case 'CREATE_PDF_WITH_SIGN':
                $activity['Properties']['TemplateId'] = (string) CrmDocumentTemplateVariable::getByName('Отмена_командировки_с_подписями');
                break;
            case 'CREATE_PDF':
                $activity['Properties']['TemplateId'] = (string) CrmDocumentTemplateVariable::getByName('Отмена_командировки');
                break;
            case 'A76709_33796_22058_61120':
                $activity['Properties']['Users'][1] = GroupVariable::getGroupString('travel_executor_esc');
                break;
            case 'A8901_76662_30105_87807':
                $activity['Properties']['MessageUserTo'][0] = GroupVariable::getGroupString('travel_executor_esc');
                break;
            case 'A75213_88932_87388_4138':
                $activity['Properties']['MailUserToArray'][0] = GroupVariable::getGroupString('travel_executor_esc');
                break;
        }
    }
}
```

Применение класса шаблона в миграциях
=====================================

```
use Bitrix\Main\IO\File;
use BizprocMigration\Admin\Import\ImportController;
use QSOFT\BizprocMigration\BizprocTemplate;
use Exception;
use Sprint\Migration\Version;

class QSOFT_266742_CANCEL_ASSIGNMENT_STATUS_8_20231003150351 extends Version
{
    protected $description = 'Миграция на изменение БП Шаг 8-8.1 Отмены командировок';

    protected $moduleVersion = '3.14.6';

    protected const PATH_TO_BP = __DIR__ . '/qsoft_266742_cancel_assignment_status_8_20231003150351_files/';

    protected const BP_UP = 'bp_433_cancel_assignment_status_8.php';

    protected const BP_DOWN = 'bp_433_cancel_assignment_status_8_old.php';

    protected const BP_TEMPLATE_CLASS = 'BPCancelAssignmentStatusEight.php';

    /**
     * @return bool
     * @throws Exception
     */
    public function up(): bool
    {
        require_once(self::PATH_TO_BP . self::BP_TEMPLATE_CLASS);

        /** @var BizprocTemplate  $className */
        $className = str_replace('.php', '', self::BP_TEMPLATE_CLASS);

        $file = new File(self::PATH_TO_BP . self::BP_UP);

        $obTemplate = $className::initFromFile($file);

        (new ImportController($obTemplate))->import();

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function down(): bool
    {
        require_once(self::PATH_TO_BP . self::BP_TEMPLATE_CLASS);

        /** @var BizprocTemplate  $className */
        $className = str_replace('.php', '', self::BP_TEMPLATE_CLASS);

        $file = new File(self::PATH_TO_BP . self::BP_DOWN);

        $obTemplate = $className::initFromFile($file);

        (new ImportController($obTemplate))->import();

        return true;
    }
}

```