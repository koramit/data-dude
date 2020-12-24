<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sublime Dude</title>
</head>

<body>
    <h1>Data Dude</h1>

    <div id="feedback"></div>

    <script>
        var request = new XMLHttpRequest();
        var form = null;
        var dom = document.createElement("body");
        var deads = ['Natural', 'Accident', 'Suicide', 'Homicide', 'During investigations', 'Unspecified'];
        var feedback = document.getElementById('feedback');

        function runDudeSync(start, stop) {
            for (start; start < stop; start++) {
                feedback.innerHTML = start;
                duDudeSync(start);
            }
            feedback.innerHTML = 'done';
        }

        function duDudeSync(id) {
            request.open('GET', `/storage/${parseInt(id/10000)}/dc_${id}.html`, false);
            request.send(null);
            if (request.status !== 200) {
                return;
            }
            dom.innerHTML = request.responseText.substring(
                request.responseText.indexOf("<body>") + 6,
                request.responseText.indexOf("</body>") - 1
            );
            form = extractDude(dom);
            request.open('POST', `/dudes/dc/${id}`, false);
            request.send(form);
        }

        function extractDude(form) {
            let data = {};
            let item = null;

            let data1 = new FormData();

            data1.append('an', extractValue(form, '#AN', 'value'));
            data1.append('hn', extractValue(form, '#HN', 'value'));
            data1.append('patient_name', extractValue(form, '#pname', 'value'));
            data1.append('patient_age', extractValue(form, '#Age', 'value'));
            data1.append('encountered_at', extractValue(form, '#Admit_Date', 'value'));
            data1.append('discharged_at', extractValue(form, '#Discharge_Date', 'value'));
            data1.append('length_of_stay', extractValue(form, '#inhospital', 'value'));

            // ward
            data.ward = extractValue(form, '#MD_Ward', 'innerText', true);

            // attending
            item = extractValue(form, '#AttendingStaff', 'innerHTML', true);
            if (item) {
                data.attending_staff = item.split(' | ')[0];
                data.attending_staff_division = item.split(' | ')[1];
            } else {
                data.attending_staff = null;
                data.attending_staff_division = null;
            }

            // author
            item = extractValue(form, '#Resident', 'innerHTML', true);
            if (item) {
                data.author = item.split(' | ')[1];
                data.author_pln = item.split(' | ')[0];
            } else {
                data.author = null;
                data.author_pln = null;
            }

            // division
            let division = extractValue(form, 'select[name=division1]', 'innerText', true);
            data.primary_division = division ? division.split(' | ')[1] : null;
            division = extractValue(form, 'select[name=division2]', 'innerText', true);
            data.secondary_division = division ? division.split(' | ')[1] : null;

            data.principal_diagnosis = extractValue(form, '#PrincipalDx', 'innerText');
            data.admit_reason = extractValue(form, '#ReasonForAdmission', 'innerText');
            data.comorbids = extractValue(form, '#Comorbids', 'innerText');
            data.complications = extractValue(form, '#Complication', 'innerText');
            data.external_cause = extractValue(form, '#Extcause', 'innerText');
            data.other_diagnosis = extractValue(form, '#OtherDiag', 'innerText');
            data.OR_procedures = extractValue(form, '#OrProcedure', 'innerText');
            data.non_OR_Procedures = extractValue(form, '#NonOrProcedure', 'innerText');
            data.chief_complaint = extractValue(form, '#CheifComplaint', 'innerText');
            data.significant_findings = extractValue(form, '#SignFinding', 'innerText');
            data.significant_procedures = extractValue(form, '#SignProcedure', 'innerText');
            data.hospital_course = extractValue(form, '#Hosp', 'innerText');
            data.condition_upon_discharge = extractValue(form, '#DC', 'innerText');
            data.follow_up_instruction = extractValue(form, '#FollowInstruction', 'innerText');

            // discharge
            let discharge = extractValue(form, '#ds', 'innerText', true);
            data.discharge_status = discharge || null;
            discharge = extractValue(form, '#dt', 'innerText', true);
            data.discharge_type = discharge || null;

            data.significant_medications = extractValue(form, 'WardRx', 'innerText');
            data.home_medications = extractValue(form, 'HomeRx', 'innerText');
            data.interesting_case = extractValue(form, '#InterestingCase', 'checked');
            data.completed = extractValue(form, '#Complete', 'checked');

            if (data.discharge_status === 'DEAD') {
                data.dead_report_charecter_of_death = extractValue(form, 'input[name=COD][checked]', 'value');
                if (data.dead_report_charecter_of_death) {
                    data.dead_report_charecter_of_death = deads[parseInt(data.dead_report_charecter_of_death) - 1];
                }
                data.dead_report_cause_of_dead_a = extractValue(form, '#LeadingtoDeath', 'innerText');
                data.dead_report_cause_of_dead_b = extractValue(form, '#Dueto1', 'innerText');
                data.dead_report_cause_of_dead_c = extractValue(form, '#Dueto2', 'innerText');
                data.dead_report_cause_of_dead_d = extractValue(form, '#Dueto3', 'innerText');
                data.dead_report_other_significant_conditions = extractValue(form, '#OtherSign', 'innerText');
            }

            return data1;
        }

        function extractValue(node, query, attribute, isSelect = false) {
            if (isSelect) {
                if (node.querySelector(query) && node.querySelector(query).querySelector('option[selected]')) {
                    return node.querySelector(query)
                        .querySelector('option[selected]')
                        .innerHTML.replaceAll('&nbsp;', ' ');
                } else {
                    return null;
                }
            } else {
                if (node.querySelector(query)) {
                    switch (attribute) {
                        case 'value':
                            return node.querySelector(query).value;
                        case 'checked':
                            return node.querySelector(query).checked;
                        case 'innerText':
                            return node.querySelector(query).innerText.trim().trim("\n");
                        case 'innerHTML':
                            return (node.querySelector(query).innerHTML).replaceAll('&nbsp;', '');
                        default:
                            return node.querySelector(query).getAttribute('attribute');
                    }
                } else {
                    return null
                }
            }
        }

    </script>
</body>

</html>
