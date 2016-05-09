<script>
    function pageLoad() {
<?php
if ($contestMode) {
    echo "loadContest(); ";
}
?>

        var alerts = <?php
if (isset($_GET['alert'])) {
    echo $_GET['alert'];
} else {
    echo 0;
}
?>;
        switch (alerts) {
            case 0:
                break;
            case 1:
                $('#alert').removeClass();
                $('#alert').addClass("alert")
                $('#alert').addClass("alert-success");
                $('#alertTitle').html("New File Created");
                $('#alertMessage').html("Your file has been created successfully!");
                $('#alert').show();
                $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                    $("#alert").hide();
                });
                break;
            default:
                break;
        }
    }
    function loadContest() {
<?php if ($contestMode) { ?>
            hourCheck(<?php echo $contest_associations['contestId']; ?>, 'on-time');
<?php } ?>
    }
    function newFile() {

        $.ajax({
            url: "http://njit1.initiateid.com/imaginarium/requests/new.php",
            method: "POST",
            data: {
                filename: document.getElementById("newFileName").value,
                extension: document.getElementById("newFileExt").value,
                folder: document.getElementById("newFolder").value
            },
            success: function (data) {
                $('#newFile').modal('hide');
                if (data.valueOf() != "0") {
                    if (document.getElementById("newFileOpen").checked) {
                        window.location.replace("http://njit1.initiateid.com/imagine_" + data + "_1/");
                    } else {
                        window.location.replace("http://njit1.initiateid.com/imagine_" + <?php echo $fileId ?> + "_1/");
                    }
                } else {
                    $('#alert').removeClass();
                    $('#alert').addClass("alert")
                    $('#alert').addClass("alert-danger");
                    $('#alertTitle').html("Creating Failed");
                    $('#alertMessage').html("An error had occured while creating file.");
                    $('#alert').show().stop(true, true);
                    $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                        $("#alert").hide();
                    });
                }
            }
        });
    }
    function save() {
        $.ajax({
            url: "http://njit1.initiateid.com/imaginarium/requests/save.php",
            method: "POST",
            data: {
                fileId: <?php echo $fileId; ?>,
                content: editor.getSession().getValue(),
                action: "save"
            },
            success: function (data) {
                $('#saveFile').modal('hide');
                if (data.valueOf() == "1") {
                    $('#alert').removeClass();
                    $('#alert').addClass("alert")
                    $('#alert').addClass("alert-success");
                    $('#alertTitle').html("Saved!");
                    $('#alertMessage').html("Your file has been saved successfully!");
                    $('#alert').show().stop(true, true);
                    $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                        $("#alert").hide();
                    });
                } else {
                    $('#alert').removeClass();
                    $('#alert').addClass("alert")
                    $('#alert').addClass("alert-danger");
                    $('#alertTitle').html("Saving Failed");
                    $('#alertMessage').html("An error had occured while saving.");
                    $('#alert').show().stop(true, true);
                    $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                        $("#alert").hide();
                    });
                }
            }
        });
    }
    $(window).bind('keydown', function (event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    save();
                    break;
            }
        }
    });
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    var i = 0;
    var dragging = false;
    $('#dragbar').mousedown(function (e) {
        e.preventDefault();

        dragging = true;
        var main = $('#main');
        var ghostbar = $('<div>',
                {id: 'ghostbar',
                    css: {
                        height: main.outerHeight(),
                        top: main.offset().top,
                        left: main.offset().left
                    }
                }).appendTo('body');

        $(document).mousemove(function (e) {
            ghostbar.css("left", e.pageX + 2);
        });

    });

    $(document).mouseup(function (e) {
        if (dragging)
        {
            var percentage = (e.pageX / window.innerWidth) * 100;
            var mainPercentage = 100 - percentage;
            $('#editorPane').css("width", percentage + "%");
            $('#hints').css("width", mainPercentage + "%");
            $('#ghostbar').remove();
            $(document).unbind('mousemove');
            dragging = false;
        }
    });
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/chrome");

<?php
switch ($file['extension']) {
    case 'java':
        echo 'editor.getSession().setMode("ace/mode/java");';
        break;
    case 'cpp':
        echo 'editor.getSession().setMode("ace/mode/c_cpp");';
        break;
    case 'py':
        echo 'editor.getSession().setMode("ace/mode/python");';
        break;
    default:
        echo 'editor.getSession().setMode("ace/mode/text");';
        break;
}
echo PHP_EOL;
?>
    editor.setFontSize(16);
    editor.$blockScrolling = Infinity
    function clearEditor() {
        editor.setValue("", 0);
    }
    function submitForm() {
        document.getElementById('code').value = editor.getSession().getValue();
        //document.getElementById('inputs').value = editor.getSession().getValue();
        document.getElementById("form").submit();
    }
    function savery() {
        document.getElementById('codeContent').value = editor.getSession().getValue();
        //document.getElementById('inputs').value = editor.getSession().getValue();
        document.getElementById("saveForm").submit();
    }
    function execute() {
		save();
        select = document.getElementById("execRunnable");
        $('#execResult').html('<i class="fa fa-gear fa-spin" style="font-size:48px"></i><i class="fa fa-gear fa-spin" style="font-size:48px"></i><i class="fa fa-gear fa-spin" style="font-size:48px"></i><h4>Executing your code. Hang tight!</h4>');
        $.ajax({
            url: "http://njit1.initiateid.com/imaginarium/execution/execution.php",
            method: "POST",
            data: {
                arguments: document.getElementById("execArgs").value,
                watch: document.getElementById("execWatch").value,
                runnable: select.options[select.selectedIndex].text,
                folder: <?php echo $folderId; ?>
            },
            success: function (data) {
                //alert(data);
                $('#execResult').html(data);
                $('#execute').modal('hide');
                $('#execution').modal('show');
            }
        });
    }

<?php if ($contestMode) { ?>
	function submitOneAnswer(e){

		$('#submissionConfirm').modal('toggle').on('click', function(e2){
			var target = $(e2.target)[0].childNodes;
			if(target[0].nodeValue == "Submit") {
				$('#submissionConfirm').modal('hide');
				$('#submission').modal('show');

				// Submission AJAX
				$.ajax({
					url: "./middleware/contest-grading3.php",
					method: "POST",
					data: {
						fileID: <?php echo $fileId; ?>,
						qid: e.id,
						teamID: <?php echo $teamId; ?>,
						type: 'sub one' // Signifies a single submission
					},
					success: function(data){
						var obj = JSON.parse(data);

						if(isNaN(obj['stat'])){
							console.log(obj['stat']);

						} else {
							// Grader AJAX
							$.ajax({
								url: "./middleware/contest-grading3.php",
								method: "POST",
								data: {
									info: obj['info'],
									qid: e.id,
									contestID: <?php echo $contest_associations['contestId']; ?>,
									teamID: <?php echo $teamId; ?>,
									sub: obj['stat'],
									type: 'grade one'
								}
							});

							window.location.replace("./imaginarium/next.php?contest=<?php echo $contest_associations['contestId'];?>");
						}
					}
				});

			}
		});
	}
<?php } ?>

<?php if ($contestMode) { ?>
	function submitAllAnswers_withOptions(){
		$('#submissionConfirmAll').modal('toggle').on('click', function(e2){
			var target = $(e2.target)[0].childNodes;
			if(target[0].nodeValue == "Submit") {
				$('#submissionConfirmAll').modal('hide');
				$('#submissionAll').modal('show');

				// Submission AJAX
				$.ajax({
					url: "./middleware/contest-grading3.php",
					method: "POST",
					data: {
						contestID: <?php echo $contest_associations['contestId']; ?>,
						teamID: <?php echo $teamId; ?>,
						type: 'sub multi' // Signifies a single submission
					},
					success: function(data){
						var obj = JSON.parse(data);
						setTimeout(function(){
							if(obj['stat'] != ''){
								var modal = document.getElementById("submission-content").childNodes[1].childNodes;
								var sub_foot = document.getElementById("submission-footer");

									modal[1].innerHTML = "Submissions Failed";
									modal[3].src = "./images/redX.png";
									modal[3].style = "width:100px;height:100px;";
									modal[5].style = "white-space: pre-wrap";
									modal[5].innerHTML = "\nError Details:\n\n"+obj['stat'];

									sub_foot.hidden = false;

							} else {
								var modal = document.getElementById("submission-content").childNodes[1].childNodes;
								var sub_foot = document.getElementById("submission-footer");
								var i = 5;
									modal[1].innerHTML = "Submissions Success";
									modal[3].src = "./images/green.png";
									modal[3].style = "width:100px;height:100px;";
									modal[5].style = "white-space: pre-wrap";

								setInterval(function(){
									if(i == 0){
										location.replace("http://njit1.initiateid.com");
										// Grader AJAX
										$.ajax({
											url: "./middleware/contest-grading3.php",
											method: "POST",
											data: {
												contestID: <?php echo $contest_associations['contestId']; ?>,
												teamID: <?php echo $teamId; ?>,
												subs: obj['subs'],
												type: 'grade multi'
											}
										});
									}
									if(i < 0)
										modal[5].innerHTML = "\nPlease Wait";
									else
										modal[5].innerHTML = "\nYou wil be redirected to the home-page in " + i;
									i--;
								}, 1000);
							}
						}, 2000);
					}
				});
			}
		});
	}
<?php } ?>

<?php if ($contestMode) { ?>
	function submitAllAnswers(){
		// Submission AJAX
		$.ajax({
			url: "./middleware/contest-grading3.php",
			method: "POST",
			data: {
				contestID: <?php echo $contest_associations['contestId']; ?>,
				teamID: <?php echo $teamId; ?>,
				type: 'sub multi' // Signifies a single submission
			},
			success: function(data){
				var obj = JSON.parse(data);

				setTimeout(function(){
					if(obj['stat'] != ''){
						var modal = document.getElementById("submission-content").childNodes[1].childNodes;
						var sub_foot = document.getElementById("submission-footer");

							modal[1].innerHTML = "Submissions Failed";
							modal[3].src = "./images/redX.png";
							modal[3].style = "width:100px;height:100px;";
							modal[5].style = "white-space: pre-wrap";
							modal[5].innerHTML = "\nError Details:\n\n"+obj['stat'];

							sub_foot.hidden = false;

					} else {
						var modal = document.getElementById("submission-content").childNodes[1].childNodes;
						var sub_foot = document.getElementById("submission-footer");
						var i = 5;
							modal[1].innerHTML = "Submissions Success";
							modal[3].src = "./images/green.png";
							modal[3].style = "width:100px;height:100px;";
							modal[5].style = "white-space: pre-wrap";

						setInterval(function(){
							if(i == 0){
								location.replace("http://njit1.initiateid.com");
								// Grader AJAX
								$.ajax({
									url: "./middleware/contest-grading3.php",
									method: "POST",
									data: {
										contestID: <?php echo $contest_associations['contestId']; ?>,
										teamID: <?php echo $teamId; ?>,
										subs: obj['subs'],
										type: 'grade multi'
									}
								});
							}
							if(i <= 0)
								modal[5].innerHTML = "\nPlease Wait";
							else
								modal[5].innerHTML = "\nYou wil be redirected to the home-page in " + i;
							i--;
						}, 1000);
					}
				}, 2000);
			}
		});
	}
<?php } ?>

function reSubmitAll(){
	var modal = document.getElementById("submission-content").childNodes[1].childNodes;
	var sub_foot = document.getElementById("submission-footer");
		modal[1].innerHTML = "Submissions in Prograss";
		modal[3].src = "./images/loading.gif";
		modal[3].style = "width:100px;height:170px;top:-20%";
		sub_foot.hidden = true;
	submitAllAnswers();
}
</script>
