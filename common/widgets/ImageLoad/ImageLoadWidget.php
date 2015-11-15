<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 06.10.2015
 * Time: 19:29
 */

namespace common\widgets\ImageLoad;

use yii\web\View;
use yii\base\Widget;
use yii\helpers\Json;
use common\widgets\ImageLoad\assets\CropperAsset;
use common\widgets\ImageLoad\models\ImageForm;
use yii\helpers\Url;

class ImageLoadWidget extends Widget
{
    public $modelName;
    public $id;
    public $object_id;
    public $imagesObject;
    public $images_num;
    public $images_label;
    public $images_temp;
    public $imageSmallWidth;
    public $imageSmallHeight;
    public $deleteUrl;
    public $classesWidget;
    public $headerModal;
    public $sizeModal;
    public $baseUrl;
    public $imagePath;
    public $noImage;
    public $options = [];
    public $pluginOptions = [];
    public $cropBoxData = [];
    public $canvasData = [];

    private $modelImageForm;

    public function init()
    {
        parent::init();
        $this->modelImageForm = new ImageForm();
        $this->deleteUrl = Url::to(['images/delete-avatar']);
        $this->registerClientScript();
    }

    public function run()
    {
        $this->options['id'] = $this->getId();
        $buttonOptions = $this->options;
        unset($buttonOptions['id']);

        return $this->render(
            'image',
            [
                'widget' => $this,
                'modelImageForm' => $this->modelImageForm,
            ]);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        // Регистрация виджета
        CropperAsset::register($view);
        // Пользовательские настройки переводим в JSON
        $options = Json::encode($this->pluginOptions);
        $cropBoxData = Json::encode($this->cropBoxData);
        $canvasData = Json::encode($this->canvasData);

        $imageClass = $this->classesWidget['imageClass'];
        $buttonDeleteClass = $this->classesWidget['buttonDeleteClass'];
        $imageContainerClass = $this->classesWidget['imageContainerClass'];
        $formImagesContainerClass = $this->classesWidget['formImagesContainerClass'];

        $js = <<< JS
            var loadFile = function(event) {                                // Создается объект события, который срабатывает при выборе нового изображения
                var output = document.getElementById("previewImg-$this->id");         // output - объект, куда будет помещено выбранное изображение
                output.src = URL.createObjectURL(event.target.files[0]);    // в свойство src объекта output помещает url выбранного изображения\
                $("#modal-$this->id").modal('show');                  // открывает модальное окно с ID "#$this->id", запускается перед событием shown.bs.modal
            };

            var deleteImage = function(event) {                                // Функция для удаления изображения
                if (confirm("Удалить изображение?")) {                                   // подтверждение удаления
                    var imageData = JSON.stringify({
                        modelName: "$this->modelName",
                        id: "$this->id",
                        object_id: "$this->object_id",
                        image_id: window.idImage,
                        images_num: "$this->images_num",
                        images_label: "$this->images_label",
                        images_temp: "$this->images_temp",
                        imageSmallWidth: "$this->imageSmallWidth",
                        imageSmallHeight: "$this->imageSmallHeight",
                        deleteUrl: "$this->deleteUrl",
                        baseUrl: "$this->baseUrl",
                        imagePath: "$this->imagePath",
                        noImage: "$this->noImage",
                        imageClass: "$imageClass",
                        buttonDeleteClass: "$buttonDeleteClass",
                        imageContainerClass: "$imageContainerClass",
                        formImagesContainerClass: "$formImagesContainerClass"
                    });
                    $.pjax({
                        type: "POST",
                        url: "$this->deleteUrl",
                        data: {imageData: imageData},
                        container: "#images-widget",
                        push: false
                    });
                } else {
                return false;
                }
            };
JS;
        $view->registerJs($js, View::POS_HEAD);                    // Событие, загрзки файла, регистрируем в head

        $js = <<< JS
            var modalBox = $("#modal-$this->id"),                                 // modalBox - объект модального окна с ID "#$this->id"
                image = $("#modal-$this->id .crop-image-container > img"),        // селектор для выбранного изображения
                cropBoxData = $cropBoxData,
                canvasData = $canvasData,
                cropUrl;                                                    // путь контроллер/действие, куда будут отправлятся pjax данные

            modalBox.on("shown.bs.modal", function (event) {                // событие, перед фактическим показом модального окна, выполняется после события show
                cropUrl = $("#crop-url-$this->id").attr("data-crop-url");   // в объект cropUrl помещаем маршрут из свойства data-crop-url элемента "#crop-url-$this->id"
                image.cropper($.extend({                                    // $.extend объеденяем объекты built и dragend, результат будет записан в built
                    built: function () {                                    // событие происходит когда Cropper полностью построен
                        // Начальные настройки изображения
                        image.cropper('setCropBoxData', cropBoxData);
                        image.cropper('setCanvasData', canvasData);
                    },
                    dragend: function() {                                   // событие завершения выделения и получения данных
                        cropBoxData = image.cropper('getCropBoxData');      // получение состояния рамки
                        canvasData = image.cropper('getCanvasData');        // получение состояние холста
                    }
                }, $options));                                              // дополнительные настройки

            }).on('hidden.bs.modal', function () {                          // событие выполняется перед фактическим закрытием модального окна и после события hide
                cropBoxData = image.cropper('getCropBoxData');              // получение конечных данных рамки
                canvasData = image.cropper('getCanvasData');                // получение конечных данных холста
                image.cropper('destroy');                                   // уничтожает Cropper и удаляет экземпляр из изображения
            });

            $(document).on("click", "#rotate-left-$this->id", function(e) { // при нажатие на элемент "#rotate-left-$this->id" происходит поворот влево на 90 градусов
                image.cropper("rotate", -90);
            });

            $(document).on("click", "#rotate-right-$this->id", function(e) {    // при нажатие на элемент "#rotate-right-$this->id" происходит поворот вправо на 90 градусов
                image.cropper("rotate", 90);
            });
JS;
        $view->registerJs($js);

        $js = <<< JS
                $(document).on("click", "#modal-$this->id .crop-submit", function(e) {    // событие нажатия на кнопку с классом .crop-submit в элементе с ID "#$this->id"
                    e.preventDefault();                                             // событие не сработает по умолчанию
                    // console.log(image.cropper("getData"));                       // вывод объекта image.cropper("getData") в консоль
                    var form = $("#image-form-$this->id");

                    cropBoxData = image.cropper('getCropBoxData');              // получение конечных данных рамки
                    canvasData = image.cropper('getCanvasData');                // получение конечных данных холста

                    var cropData = JSON.stringify(image.cropper("getData"));

                    form.trigger('submit');

                    form.on("beforeSubmit", function(e) {

                        $("#image_id-$this->id").attr("value", window.idImage);      // отправляем ID изображения, которое нужно изменить
                        var cropData = JSON.stringify(image.cropper("getData"));
                        $("#imageCrop-$this->id").attr("value", cropData);
                    });
                    modalBox.modal("hide");                                     // событие выполняется перед фактическим закрытием модального окна и перед событием hidden.bs.modal
                });
JS;
        $view->registerJs($js);
    }
}