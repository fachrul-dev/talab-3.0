//explain params
//
//1. class_input_file = selector class/name for input file - required
//2. class_value = selector class/name for saving id file as array for first data write - required if DataID not exist
//3. DataID = id for data in table ClassName - optional
//4. FileField = name of key many many image - required
//5. ClassName = tables / class name - required

function unique(array){
    return array.filter(function(el, index, arr) {
        return index === arr.indexOf(el);
    });
}

function fileinput_fikasa(class_input_file, class_value, DataID, FileField, ClassName, load_url, upload_url, delete_url, token){
            var loading = '<i attr-id="'+FileField+'" class="fa loading fa-circle-o-notch fa-spin"></i>';
            var id = DataID;
            if($(class_input_file).data('fileinput')){
                $(class_input_file).fileinput('destroy')
            }

            $(class_input_file).after(loading);
            $(class_input_file).hide();

            if(class_value){
                $(class_value).val('[]');
            }

            if(!load_url){
                load_url = 'file/loadberkas'
            }
            if(!upload_url){
                upload_url = "file/uploadfile"
            }
            if(!delete_url){
                delete_url = "file/deletefile"
            }

            var browse_on_click = true


//            var id = '$TransaksiClosing.ID'
            var arr_preview = []
            var arr_preview_config = []
            var result = []
            $.ajax({
                url:load_url,
                type:'get',
                data:{
                    'ID' : id,
                    'FileField':FileField,
                    'ClassName':ClassName,
                    'Token':token
                },
                dataType:'json',
                success:function(data){
                    arr_preview = data.initialPreview
                    arr_preview_config = data.initialPreviewConfig
                    if(data.DataID && class_value){
                        $(class_value).val(JSON.stringify(data.DataID));
                    }
                }

            }).done(function(){
                $(class_input_file).show();
                $(".loading[attr-id='"+FileField+"']").remove();
                $(class_input_file).fileinput({


                  language: 'id',

                  uploadUrl: upload_url,
                  uploadAsync: true,
                  deleteUrl: delete_url,
                  showUpload: false, // hide upload button
                  showRemove: false, // hide upload button
                  overwriteInitial: false,
                  browseOnZoneClick: browse_on_click,

                  initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup

                  initialPreviewFileType: 'image',
                  initialPreview: arr_preview,
                  initialPreviewConfig: arr_preview_config,
                  previewFileIconSettings: {
                        'doc': '<i class="fas fa-file-word text-primary"></i>',
                        'xls': '<i class="fas fa-file-excel text-success"></i>',
                        'csv': '<i class="fas fa-file-excel text-success"></i>',
                        'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                        'zip': '<i class="fas fa-file-archive text-muted"></i>',
                        'htm': '<i class="fas fa-file-code text-info"></i>',
                        'txt': '<i class="fas fa-file-alt text-info"></i>',
                        'mov': '<i class="fas fa-file-video text-warning"></i>',
                        'mp3': '<i class="fas fa-file-audio text-warning"></i>',
                    },
                    uploadExtraData: {
                        'DataID' : id,
                        'FileField':FileField,
                        'ClassName':ClassName,

                    },

                }).on("filebatchselected", function(event, files) {
                    $(class_input_file).fileinput("upload");
                }).on('fileuploaded', function(event, data, previewId, index, fileId) {
                    if(class_value){
    //                    console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                        var valuenya = [];
                         var form = data.form, files = data.files, extra = data.extra,
                                response = data.response, reader = data.reader;
//                        console.log(extra, response,'hahahahaha')
                        var berkas_value = $(class_value).val();
                        berkas_value = jQuery.parseJSON(berkas_value);
    //                    console.log(berkas_value.length, 'haha')
                        if(berkas_value.length){
                            valuenya = berkas_value
                        }
                        $.merge( valuenya, response.ID)
                        console.log(valuenya);

                        valuenya = unique(valuenya)


    //                    berkas_value.push(response);
                        $(class_value).val(JSON.stringify(valuenya));
                    }
                }).on('fileuploaderror', function(event, data, msg) {
                    //console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
                }).on('filebatchuploadsuccess', function(event, data, previewId, index) {
//                    alert('haha')
                }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
//                    console.log('File Batch Uploaded', preview, config, tags, extraData);
//                    console.log('ini',result)

                }).on('filebeforedelete', function() {

                    // var aborted = !window.confirm('Are you sure you want to delete this file?');
                    // if (aborted) {
                    //     window.alert('File deletion was aborted! ' + krajeeGetCount('file-5'));
                    // };
                    // return aborted;
                }).on('filedeleted', function(event, key) {
                    if(class_value){
                        console.log('Key = ' + key);
                        var berkas_value = $(class_value).val();
                        berkas_value = jQuery.parseJSON(berkas_value);
                        console.log(berkas_value)
                        var index = berkas_value.indexOf(key);
                        console.log(index)
                        if (index !== -1) {
                          berkas_value.splice(index, 1);
                        }
                        berkas_value = unique(berkas_value)
                        $(class_value).val(JSON.stringify(berkas_value));
                    }
                });
            }).always(function(){
                $(".loading[attr-id='"+FileField+"']").remove();
            })
    }
