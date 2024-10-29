var AQVG_jq = jQuery.noConflict();
AQVG_jq(document).ready(function() {
    AQVG_jq.validator.addMethod('youtubeURL', function (value) { 
    return /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$|^.*\.(mp4|mov|3gp|mkv|avi)$/.test(value); 
    });
    AQVG_jq('#all_video').DataTable();
    AQVG_jq("#addingvideo").validate({
        rules: {
            "AQVG_video_title": {
                required: true,
                rangelength: [4, 20]
            },
            "AQVG_video_url": {
                required: true,
                minlength: 10,
                maxlength: 400,
                youtubeURL: true
            }
        },
        messages:{
        	"AQVG_video_title": {
                required: "Please enter the video title",
                rangelength: "Please enter the video title between 4-20 Only"
            },
            "AQVG_video_url": {
                required: "Please enter the URL.",
                minlength: "URL Seems to be too short.",
                maxlength: "URL Seems to be too big.",
                youtubeURL: "Please enter valid youtube url or media url."
            } 
        }
	});
    AQVG_jq('#editingvideo').validate({
        rules: {
            "AQVG_video_title": {
                required: true,
                rangelength: [4, 20]
            },
            "AQVG_video_url": {
                required: true,
                minlength: 10,
                maxlength: 400,
                youtubeURL: true
            }
        },
        messages:{
            "AQVG_video_title": {
                required: "Please enter the video title",
                rangelength: "Please enter the video title between 4-20 Only"
            },
            "AQVG_video_url": {
                required: "Please enter the URL.",
                minlength: "URL Seems to be too short.",
                maxlength: "URL Seems to be too big.",
                youtubeURL: "Please enter valid youtube url or media url."
            } 
        }
    })
});