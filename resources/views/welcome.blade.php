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

    <input type="number" placeholder="start" id="input_start">
    <input type="number" placeholder="stop" id="input_stop">
    <button
        onclick="runDudeSync(document.getElementById('input_start').value, document.getElementById('input_stop').value);">run</button>

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
            request.open('POST', `/dudes/discharge/${id}`, false);
            request.send(form);
        }

        function extractDude(form) {
            let data = new FormData();
            let item = null;

            data.append('an', extractValue(form, '#AN', 'value'));
            data.append('hn', extractValue(form, '#HN', 'value'));
            data.append('patient_name', extractValue(form, '#pname', 'value'));
            data.append('patient_age', extractValue(form, '#Age', 'value'));
            data.append('encountered_at', extractValue(form, '#Admit_Date', 'value'));
            data.append('discharged_at', extractValue(form, '#Discharge_Date', 'value'));
            data.append('length_of_stay', extractValue(form, '#inhospital', 'value'));

            // ward
            data.append('ward', extractValue(form, '#MD_Ward', 'innerText', true));

            // attending
            item = extractValue(form, '#AttendingStaff', 'innerHTML', true);
            if (item) {
                data.append('attending_staff', item.split(' | ')[0]);
                data.append('attending_staff_division', item.split(' | ')[1]);
            } else {
                data.append('attending_staff', null);
                data.append('attending_staff_division', null);
            }

            // author
            item = extractValue(form, '#Resident', 'innerHTML', true);
            if (item) {
                data.append('author', item.split(' | ')[1]);
                data.append('author_pln', item.split(' | ')[0]);
            } else {
                data.append('author', null);
                data.append('author_pln', null);
            }

            // division
            let division = extractValue(form, 'select[name=division1]', 'innerText', true);
            data.append('primary_division', division ? division.split(' | ')[1] : null);
            division = extractValue(form, 'select[name=division2]', 'innerText', true);
            data.append('secondary_division', division ? division.split(' | ')[1] : null);

            data.append('principal_diagnosis', extractValue(form, '#PrincipalDx', 'innerText'));
            data.append('admit_reason', extractValue(form, '#ReasonForAdmission', 'innerText'));
            data.append('comorbids', extractValue(form, '#Comorbids', 'innerText'));
            data.append('complications', extractValue(form, '#Complication', 'innerText'));
            data.append('external_cause', extractValue(form, '#Extcause', 'innerText'));
            data.append('other_diagnosis', extractValue(form, '#OtherDiag', 'innerText'));
            data.append('OR_procedures', extractValue(form, '#OrProcedure', 'innerText'));
            data.append('non_OR_Procedures', extractValue(form, '#NonOrProcedure', 'innerText'));
            data.append('chief_complaint', extractValue(form, '#CheifComplaint', 'innerText'));
            data.append('significant_findings', extractValue(form, '#SignFinding', 'innerText'));
            data.append('significant_procedures', extractValue(form, '#SignProcedure', 'innerText'));
            data.append('hospital_course', extractValue(form, '#Hosp', 'innerText'));
            data.append('condition_upon_discharge', extractValue(form, '#DC', 'innerText'));
            data.append('follow_up_instruction', extractValue(form, '#FollowInstruction', 'innerText'));

            // discharge
            let discharge = extractValue(form, '#ds', 'innerText', true);
            data.append('discharge_status', discharge || null);
            discharge = extractValue(form, '#dt', 'innerText', true);
            data.append('discharge_type', discharge || null);

            data.append('significant_medications', extractValue(form, 'WardRx', 'innerText'));
            data.append('home_medications', extractValue(form, 'HomeRx', 'innerText'));
            data.append('interesting_case', extractValue(form, '#InterestingCase', 'checked'));
            data.append('completed', extractValue(form, '#Complete', 'checked'));

            if (data.discharge_status === 'DEAD') {
                data.append('dead_report_charecter_of_death', extractValue(form, 'input[name=COD][checked]', 'value'));
                if (data.dead_report_charecter_of_death) {
                    data.append('dead_report_charecter_of_death', deads[parseInt(data.dead_report_charecter_of_death) -
                        1]);
                }
                data.append('dead_report_cause_of_dead_a', extractValue(form, '#LeadingtoDeath', 'innerText'));
                data.append('dead_report_cause_of_dead_b', extractValue(form, '#Dueto1', 'innerText'));
                data.append('dead_report_cause_of_dead_c', extractValue(form, '#Dueto2', 'innerText'));
                data.append('dead_report_cause_of_dead_d', extractValue(form, '#Dueto3', 'innerText'));
                data.append('dead_report_other_significant_conditions', extractValue(form, '#OtherSign', 'innerText'));
            }

            return data;
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
