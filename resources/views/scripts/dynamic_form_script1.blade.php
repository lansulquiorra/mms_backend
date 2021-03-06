<script type="text/javascript">
    $(window).on('load', function(e) {
        // console.log($(window).contentWindow.document.body.scrollHeight);

        var element = document.getElementById("wadah");
        for (i=0; i<data.length; i++) {
            var json = data[i];

            var type = json.question_type.name;

            var equal1 = type.toUpperCase() === "USERNAME";
            var equal2 = type.toUpperCase() === "CONFIRM PASSWORD";
            var equal3 = type.toUpperCase() === "PASSWORD";
            var equal4 = type.toUpperCase() === "DIVIDER TITLE";
            var equal5 = type.toUpperCase() === "DIVIDER";
            var equal6 = type.toUpperCase() === "NAME";
            var equal7 = type.toUpperCase() === "EMAIL";
            var equal8 = type.toUpperCase() === "ADDRESS";
            var equal9 = type.toUpperCase() === "FILE UPLOAD";

            var html = "";
            var qid = "";

            if (equal1) {
                html = json.question_type.html_tag.split(";");
                qid = "username";

                setFormQuestion(json, html, qid)
            } else if (equal2) {
                html = json.question_type.html_tag.split(";");
                qid = "password_confirmation";

                setFormQuestion(json, html, qid)
            } else if (equal3) {
                html = json.question_type.html_tag.split(";");
                qid = "password";

                setFormQuestion(json, html, qid)
            } else if (equal4) {
                html = json.question_type.html_tag.replace("[divider]", json.question);

                $(html).appendTo(element);

            } else if (equal5) {
                html = json.question_type.html_tag.replace("[divider]", "");

                $(html).appendTo(element);

            } else if (equal6) {
                html = json.question_type.html_tag.split(";");
                qid = "name";

                setFormQuestion(json, html, qid)
            } else if (equal7) {
                html = json.question_type.html_tag.split(";");
                qid = "email";

                setFormQuestion(json, html, qid)
            } else if (equal8) {
                var rules = json.rules_detail;
                var req = "";
                if (rules) {
                    for (var u = rules.length - 1; u >= 0; u--) {
                        var rule = rules[u]
                        if (rule.name.toUpperCase()==="REQUIRED") {
                            req = "<font color='red' size='3'>*</font>";
                        }
                    }
                }

                var valueP = "";
                var valueK = "";
                var valueA = "";
                var valuePos = "";

                for (var u = answers.length - 1; u >= 0; u--) {
                    var answer = answers[u];
                    if (answer.question == "Provinsi") {
                        valueP = answer.answer_value;
                    } else if (answer.question == "Kabupaten / Kota") {
                        valueK = answer.answer_value;
                    } else if (answer.question == "Alamat Lengkap") {
                        valueA = answer.answer_value;
                    } else if (answer.question == "Kode Pos") {
                        valuePos = answer.answer_value;
                    }
                }

                $(  "<div class='form-group'>"+
                    "<label for=id_question_Provinsi>Provinsi</label>"+
                    req+
                    "<select class=form-control name='id_question_Provinsi' id='provinsi' onchange='setDaerah(this.value)'>"+
                    "</select>"+
                    "</div>").appendTo(element);

                $(  "<div class='form-group'>"+
                    "<label for='id_question_KabKot'>Kabupaten / Kota</label>"+
                    req+
                    "<select class=form-control name='id_question_KabKot' id='daerah'>"+
                    "</select>"+
                    "</div>").appendTo(element);

                $(  "<div class='form-group'>"+
                    "<label for='id_question_Alamat'>Alamat Lengkap</label>"+
                    req+
                    "<textarea class=form-control name='id_question_Alamat'>"+valueA+"</textarea>"+
                    "</div>").appendTo(element);

                $(  "<div class='form-group'>"+
                    "<label for='id_question_KodePos'>Kode Pos</label>"+
                    req+
                    "<input type=text class=form-control name='id_question_KodePos' value='"+valuePos+"'>"+
                    "</div>").appendTo(element);

                setProvinsi(valueP, valueK);
            } else if (equal9) {
                var rules = json.rules_detail;
                var req = "";
                if (rules) {
                    for (var u = rules.length - 1; u >= 0; u--) {
                        var rule = rules[u]
                        if (rule.name.toUpperCase()==="REQUIRED") {
                            req = "<font color='red' size='3'>*</font>";
                        }
                    }
                }
                var id = json.id;
                var question = json.question;
                var value = "";

                for (var u = answers.length - 1; u >= 0; u--) {
                    var answer = answers[u];
                    if (answer.question == json.question) {
                        value = answer.answer_value;
                    }
                }

                $(  "<div class='form-group'>"+
                        "<label for='"+id+"'>"+question+"</label>"+
                        req+
                        "<div class='input-group file-caption-main'>"+
                            "<div class='form-control file-caption  kv-fileinput-caption'>"+
                                "<div class='file-caption-name' id='text-"+id+"'>"+
                                    "<i class='glyphicon glyphicon-file kv-caption-icon'></i>"+
                                    value+
                                "</div>"+
                            "</div>"+
                            "<div class='input-group-btn'>"+
                                "<div class='btn btn-primary btn-file'>"+
                                    "<i class='glyphicon glyphicon-folder-open'></i>"+
                                    "&nbsp;"+
                                    "<span class='hidden-xs'>Browse …</span>"+
                                    "<input name='id_question_fileupload_"+id+"' type='file' class='file' onchange='setImgText(this, "+id+")'>"+
                                "</div>"+
                            "</div>"+
                        "</div>"+
                    "</div>").appendTo(element);


            } else {
                var setting = json.setting;
                html = setting.html_tag.split(";");

                qid = "id_question_"+json.id;

                setFormQuestion(json, html, qid);
            }
        }

        var alb = "{{ $alb }}";
        if (alb) {
            $("<a href='' class='btn btn-primary full-width' data-toggle='modal' data-target='#insertModal' >"+
                "Next"+
                "</a>").appendTo(element);
        } else {
            $(	"<div class='form-group'>"+
                "<input class='btn btn-primary full-width' type='submit' value='Submit'>"+
                "</div>").appendTo(element);
        }

        var div1 = element.offsetHeight;
        var div2 = document.getElementById('page-wrapper').offsetHeight;
        var divh = div1 + div2;
        document.getElementById('page-wrapper').style.height = divh + 'px';
    });

    function setFormQuestion(json, html, qid) {

        var element = document.getElementById("wadah");

        var list_answer = json.list_answer;
        var options = "";

        var value = "";

        for (var u = answers.length - 1; u >= 0; u--) {
            var answer = answers[u];
            console.log(answer.question+' '+json.question);
            if (answer.question == json.question) {
                value = answer.answer_value;
            }
        }

        if (list_answer.length>0) {
            for (u = 0; u < list_answer.length; u++) {
                var answer = list_answer[u];
                if (answer.id == value) {
                    options += answer.options_tag
                        .replace("[value]", answer.id)
                        .replace("[answer]", answer.answer)
                        .replace("[name]", qid)
                        .replace(">", " selected='selected'>");
                } else {
                    options += answer.options_tag
                        .replace("[value]", answer.id)
                        .replace("[answer]", answer.answer)
                        .replace("[name]", qid);
                }
            }
        }

        var rules = json.rules_detail;
        var req = "";
        if (rules) {
            for (var i = rules.length - 1; i >= 0; i--) {
                var rule = rules[i]
                if (rule.name.toUpperCase()==="REQUIRED") {
                    req = "<font color='red' size='3'>*</font>";
                }
            }
        }

        var text_value = "";
        var input_value = ">";
        if (html[0].indexOf("textarea") !== -1) {
            text_value = value;
        } else if (html[0].indexOf("select") == -1) {
            input_value = " value='"+value+"'>";
        }

        console.log(qid+input_value);
        $(  "<div class='form-group'>"+
            "<label for='"+qid+"'>"+json.question+" :</label>"+
            req+"<br>"+
            html[0].replace("[name]>", qid+input_value)+
            options+
            text_value+
            html[1].replace("[name]", qid)+
            "</div>").appendTo(element);
    }

    function setProvinsi(selected, daerah) {
        $.ajax({
            url: "{{ url('ajax/listprovinsi') }}"
        }).done(function(datas) {
            var element = document.getElementById("provinsi");
            for (u=0; u<datas.length; u++) {
                if (datas[u].id==selected) {
                    $(
                        "<option value='"+datas[u].id+"' selected='selected'>"+datas[u].provinsi+"</option>"
                    ).appendTo(element);
                    setDaerah(selected, daerah);
                } else {
                    $("<option value='"+datas[u].id+"'>"+datas[u].provinsi+"</option>").appendTo(element);
                }
            }
        });
    }

    function setDaerah(value, selected) {
        $.ajax({
            url: "{{ url('ajax/listdaerah') }}" + "/" + value
        }).done(function(datas) {
            clearElement("daerah");
            var element = document.getElementById("daerah");
            for (u = 0; u < datas.length; u++) {
                if (datas[u].id==selected) {
                    $("<option value='"+datas[u].id+"' selected='selected'>"+datas[u].daerah+"</option>").appendTo(element);
                } else {
                    $("<option value='"+datas[u].id+"'>"+datas[u].daerah+"</option>").appendTo(element);
                }
            }
        });
    }

    function clearElement(id) {
        var select = document.getElementById(id);
        var i;
        if (select.options.length>=1) {
            for(i = select.options.length - 1 ; i >= 0 ; i--)
            {
                select.remove(i);
            }
        }
    }

    function setImgText(input, id) {
        console.log(input.files[0].name);

        var filename = input.files[0].name;
        var element = document.getElementById("text-"+id);

        element.innerHTML =
            "<i class='glyphicon glyphicon-file kv-caption-icon'></i>"+
            filename+
            "<input name='id_question_"+id+"'' type='hidden' value='"+filename+"'>";
    }
</script>