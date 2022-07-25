$(function(){
    var options =
        {
            aspectRatio: 1 / 1,
            viewMode:1,
            crop: function(e) {
                cropData = $('#selectImage').cropper("getData");
                $("#imageX").val(Math.floor(cropData.x));
                $("#imageY").val(Math.floor(cropData.y));
                $("#imageW").val(Math.floor(cropData.width));
                $("#imageH").val(Math.floor(cropData.height));
            },
            zoomable:false,
            minCropBoxWidth:162,
            minCropBoxHeight:162
        };
    $('#selectImage').cropper(options);

    $("#roomImage").change(function(){
        $('#selectImage').cropper('replace', URL.createObjectURL(this.files[0]));
    });
});