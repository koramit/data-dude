<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sublime Dude</title>
</head>

<body>
    <h1>hello</h1>

    <div id="feedback"></div>

    <script>
        var html
        var htmlStr

        function doDude(id) {
            fetch(`/storage/${parseInt(id/10000)}/dc_${id}.html`)
                .then(res => {
                    if (res.ok) {
                        return res.text();
                    } else {
                        let feedback = document.getElementById('feedback')
                        feedback.innerText = `${Date()} ${id} not found\n` + feedback.innerText
                        throw new Error("");
                    }
                }).then(text => {
                    // console.log(text)
                    htmlStr = text
                    let content = document.createElement("body");
                    const begin = text.indexOf("<body>") + 6;
                    const end = text.indexOf("</body>") - 1;
                    content.innerHTML = text.substring(begin, end);
                    document.getElementById('feedback').innerHTML = text.substring(begin, end);
                    const form = extractDude(content);
                    console.table(form);
                    html = content;
                });
        }

        function extractDude(form) {
            data = {
                an: form.querySelector('#AN').value,
                hn: form.querySelector('#HN').value,
                patient_name: form.querySelector('#pname').value,
                patient_age: form.querySelector('#Age').value,
                encountered_at: form.querySelector('#Admit_Date').value,
                discharged_at: form.querySelector('#Discharge_Date').value,
                length_of_stay: form.querySelector('#inhospital').value,
                ward: form.querySelector('#MD_Ward').querySelector('option[selected]').innerText,
                attending_staff: form.querySelector('#AttendingStaff').querySelector('option[selected]').innerText
                    .split(' | ')[0] || null,
                attending_staff_division: form.querySelector('#AttendingStaff').querySelector('option[selected]')
                    .innerText.split(' | ')[1] || null,
                author: form.querySelector('#Resident').querySelector('option[selected]').innerText
                    .split(' | ')[1] || null,
                author_pln: form.querySelector('#Resident').querySelector('option[selected]').innerText
                    .split(' | ')[0] || null,
                primary_division: form.querySelector('select[name=division1]').querySelector('option[selected]')
                    .innerText
                    .split(' | ')[1] || null,
                secondary_division: form.querySelector('select[name=division2]').querySelector('option[selected]')
                    .innerText
                    .split(' | ')[1] || null,
                principal_diagnosis: form.querySelector('#PrincipalDx').innerText,
                admit_reason: form.querySelector('#ReasonForAdmission').innerText,
                comorbids: form.querySelector('#Comorbids').innerText,
                complications: form.querySelector('#Complication').innerText,
                external_cause: form.querySelector('#Extcause').innerText,
                other_diagnosis: form.querySelector('#OtherDiag').innerText,
                OR_procedures: form.querySelector('#OrProcedure').innerText,
                non_OR_Procedures: form.querySelector('#NonOrProcedure').innerText,
                chief_complaint: form.querySelector('#CheifComplaint').innerText,
                significant_findings: form.querySelector('#SignFinding').innerText,
                significant_procedures: form.querySelector('#SignProcedure').innerText,
                hospital_course: form.querySelector('#Hosp').innerText,
                condition_upon_discharge: form.querySelector('#DC').innerText,
                follow_up_instruction: form.querySelector('#FollowInstruction').innerText,
                discharge_status: form.querySelector('#ds').querySelector('option[selected]').innerText,
                discharge_type: form.querySelector('#dt').querySelector('option[selected]').innerText,
                significant_medications: form.querySelector('#WardRx').innerText,
                home_medications: form.querySelector('#HomeRx').innerText,
                interesting_case: form.querySelector('#InterestingCase').checked,
                completed: form.querySelector('#Complete').checked,
            };
            if (data.discharge_status === 'DEAD') {
                data.dead_report_charecter_of_death = form.querySelector('input[name=COD][checked]').value;
                data.dead_report_cause_of_dead_a = form.querySelector('#LeadingtoDeath').innerText;
                data.dead_report_cause_of_dead_b = form.querySelector('#Dueto1').innerText;
                data.dead_report_cause_of_dead_c = form.querySelector('#Dueto2').innerText;
                data.dead_report_cause_of_dead_d = form.querySelector('#Dueto3').innerText;
                data.dead_report_other_significant_conditions = form.querySelector('#OtherSign').innerText;
            }
            return data;
        }

    </script>
</body>

</html>
