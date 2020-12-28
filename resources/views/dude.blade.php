<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dude</title>
</head>

<body>
    <h1>Run Dude</h1>
    @csrf
    <input type="number" placeholder="start" id="input_start">
    <input type="number" placeholder="stop" id="input_stop">
    <button
        onclick="runDudeSync(document.getElementById('input_start').value, document.getElementById('input_stop').value);">run</button>

    <div id="feedback"></div>
    <script>
        var request = new XMLHttpRequest();
        var form = null;
        var dom = document.createElement("body");
        var feedback = document.getElementById('feedback');

        function runDudeSync(start, stop) {
            for (start; start < stop; start++) {
                feedback.innerHTML = start;
                duDudeSync(start);
            }
            feedback.innerHTML = 'done';
        }

        function duDudeSync(id) {
            request.open('POST', `/call-dude/${id}`, false);
            form = new FormData();
            form.append('_token', document.querySelector('input[name=_token]').value);
            request.send(form);
            if (request.status !== 200) {
                return;
            }
            dom.innerHTML = request.responseText;
            form = extractDude();
            // request.open('POST', `/dudes/admit/${id}`, false);
            // request.send(form);
        }

        function extractDude() {
            let data = new FormData();

            // * AN: value => input#AN
            data.append('AN', extractValue(dom, 'input#AN', 'value'));

            // * HN: value => input#HN
            data.append('HN', extractValue(dom, 'input#HN', 'value'));

            // * pname: value => input#pname
            data.append('pname', extractValue(dom, 'input#pname', 'value'));

            // * gender: value => input[name=sex]
            data.append('sex', extractValue(dom, 'input[name=sex]', 'value'));

            // * Age: value => input#Age
            data.append('Age', extractValue(dom, 'input#Age', 'value'));

            // * attending: option => select[name=Staff]
            item = extractValue(dom, 'select[name=Staff]', 'innerHTML', true);
            if (item) {
                data.append('attending_staff', item.split(' | ')[0]);
                data.append('attending_staff_division', item.split(' | ')[1]);
            } else {
                data.append('attending_staff', null);
                data.append('attending_staff_division', null);
            }

            // * author_pln: value => input[name=dent_code]
            data.append('dent_code', extractValue(dom, 'input[name=dent_code]', 'value'));
            // * author_title: value => input[name=dent_pos]
            data.append('dent_pos', extractValue(dom, 'input[name=dent_pos]', 'value'));
            // * author_fname: value => input[name=dent_name]
            data.append('dent_name', extractValue(dom, 'input[name=dent_name]', 'value'));
            // * author_lname: value => input[name=dent_sure]
            data.append('dent_sure', extractValue(dom, 'input[name=dent_sure]', 'value'));
            // * date_admit: value => input[name=Admit_Date]
            data.append('Admit_Date', extractValue(dom, 'input[name=Admit_Date]', 'value'));
            // * Chief complaint: textarea #CC => innerText
            data.append('chief_complaint', extractValue(dom, '#CC', 'innerText'));
            // * Admit reason: input[name = reason][checked] => value
            data.append('admit_reason', extractValue(dom, 'input[name = reason][checked]', 'value'));
            // * History of present illness: textarea #present => innerText
            data.append('history_of_present_illness', extractValue(dom, '#present', 'innerText'));
            // * Past history: textarea #past => innerText
            data.append('past_history', extractValue(dom, '#past', 'innerText'));
            // * Women: input #forwoman => checked
            data.append('female', extractValue(dom, '#forwoman', 'checked'));
            // * Pregnancy: input[name = woman][checked] => value
            data.append('pregnancy', extractValue(dom, 'input[name=woman][checked]', 'value'));
            // * Pregnant week: input #Pregnant_week => value
            data.append('pregnant_weeks', extractValue(dom, '#Pregnant_week', 'value'));
            // * DM: input[name=DM][checked] => value
            data.append('comorbid_DM', extractValue(dom, 'input[name=DM][checked]', 'value'));
            // * DM type: input[name=DM_Type][checked] => value
            data.append('comorbid_DM_type', extractValue(dom, 'input[name=DM_Type][checked]', 'value'));
            // * DM complication DR: input #DR => checked
            data.append('comorbid_DM_DR', extractValue(dom, 'input#DR', 'checked'));
            // * DM complication Nephropathy: input#Nephro => checked
            data.append('comorbid_DM_Nephropathy', extractValue(dom, 'input#Nephro', 'checked'));
            // * DM complication Neuropathy: input#Neuro => checked
            data.append('comorbid_DM_Neuropathy', extractValue(dom, 'input#Neuro', 'checked'));
            // * DM treatment diet: input#diet => checked
            data.append('comorbid_DM_on_diet', extractValue(dom, 'input#diet', 'checked'));
            // * DM treatment oral medications: input#oral => checked
            data.append('comorbid_DM_on_medications', extractValue(dom, 'input#oral', 'checked'));
            // * DM treatment insulin: input#insulin => checked
            data.append('comorbid_DM_on_insulin', extractValue(dom, 'input#insulin', 'checked'));
            // * Hypertension: input[name=Hypertension][checked] => value
            data.append('comorbid_hypertension', extractValue(dom, 'input[name=Hypertension][checked]', 'value'));
            // * Coronary artery disease: input[name=Coronary][checked] => value
            data.append('comorbid_coronary_artery_disease', extractValue(dom, 'input[name=Coronary][checked]',
                'value'));
            // * Valvular heart disease: input[name=Valvular][checked] => value
            data.append('comorbid_valvular_heart_disease', extractValue(dom, 'input[name=Valvular][checked]', 'value'));
            // * Stroke: input[name=Stroke][checked] => value
            data.append('comorbid_stroke', extractValue(dom, 'input[name=Stroke][checked]', 'value'));
            // * COPD: input[name=COPD][checked] => value
            data.append('comorbid_COPD', extractValue(dom, 'input[name=COPD][checked]', 'value'));
            // * asthma: input[name=asthma][checked] => value
            data.append('comorbid_asthma', extractValue(dom, 'input[name=asthma][checked]', 'value'));
            // * Chronic kidney disease: input[name= kidney][checked] => value
            data.append('comorbid_CKD', extractValue(dom, 'input[name=kidney][checked]', 'value'));
            // * Hyperlipidemia: input[name=Dyslipidemia][checked] => value
            data.append('comorbid_hyperlipidemia', extractValue(dom, 'input[name=Dyslipidemia][checked]', 'value'));
            // * Cirrhosis: input[name=Cirrhosis][checked] => value
            data.append('comorbid_cirrhosis', extractValue(dom, 'input[name=Cirrhosis][checked]', 'value'));
            // * HIV: input[name=HIV][checked] => value
            data.append('comorbid_HIV', extractValue(dom, 'input[name=HIV][checked]', 'value'));
            // * AIDS: input[name=AIDS][checked] => value
            data.append('comorbid_AIDS', extractValue(dom, 'input[name=AIDS][checked]', 'value'));
            // * Epilepsy: input[name=Epilepsy][checked] => value
            data.append('comorbid_epilepsy', extractValue(dom, 'input[name=Epilepsy][checked]', 'value'));
            // * Coagulopathy: input[name=Coagulopathy][checked] => value
            data.append('comorbid_coagulopathy', extractValue(dom, 'input[name=Coagulopathy][checked]', 'value'));
            // * HBV infection: input[name=ABV][checked] => value
            data.append('comorbid_HBV', extractValue(dom, 'input[name=ABV][checked]', 'value'));
            // * HCV infection: input[name=ACV][checked] => value
            data.append('comorbid_HCV', extractValue(dom, 'input[name=ACV][checked]', 'value'));
            // * Cancer: input[name=Cancer][checked] => value
            data.append('comorbid_cancer', extractValue(dom, 'input[name=Cancer][checked]', 'value'));
            // * Cancer organs: input[name=Cancer_Detail] => value
            data.append('comorbid_cancer_organs', extractValue(dom, 'input[name=Cancer_Detail]', 'value'));
            // * Leukemia: input[name=Leukemia][checked] => value
            data.append('comorbid_leukemia', extractValue(dom, 'input[name=Leukemia][checked]', 'value'));
            // * Lymphoma: input[name=Lymphoma][checked] => value
            data.append('comorbid_lymphoma', extractValue(dom, 'input[name=Lymphoma][checked]', 'value'));
            // * Pacemaker implant: input[name=Pacemaker][checked] => value
            data.append('comorbid_pacemaker', extractValue(dom, 'input[name=Pacemaker][checked]', 'value'));
            // * Chronic arthritis: input[name=Chronic][checked] => value
            data.append('comorbid_chronic_arthritis', extractValue(dom, 'input[name=Chronic][checked]', 'value'));
            // * SLE: input[name=SLE][checked] => value
            data.append('comorbid_SLE', extractValue(dom, 'input[name=SLE][checked]', 'value'));
            // * Other autoimmune: input[name=autoimmune][checked] => value
            data.append('comorbid_other_autoimmune', extractValue(dom, 'input[name=autoimmune][checked]', 'value'));
            // * TB or other active communicable disease: input[name=TB][checked] => value
            data.append('comorbid_TB_or_other_active_communicable_disease', extractValue(dom, 'input[name=TB][checked]',
                'value'));
            // * Dementia: input[name=Dementia][checked] => value
            data.append('comorbid_dementia', extractValue(dom, 'input[name=Dementia][checked]', 'value'));
            // * Psychiatric illness: input[name=Phychiartic][checked] => value
            data.append('comorbid_psychiatric_illness', extractValue(dom, 'input[name=Phychiartic][checked]', 'value'));
            // * Other comorbids: input[name=Others_Comorbid] => value
            data.append('comorbid_other', extractValue(dom, 'input[name=Others_Comorbid]', 'value'));
            // * alcohol: input[name=alcohol][checked] => value
            data.append('alcohol', extractValue(dom, 'input[name=alcohol][checked]', 'value'));
            // * alcohol amount: input#alcohol_amount => value
            data.append('alcohol_amount', extractValue(dom, 'input#alcohol_amount', 'value'));
            // * smoking: input[name=smoking][checked] => value
            data.append('smoking', extractValue(dom, 'input[name=smoking][checked]', 'value'));
            // * smoking amount: input#smoking_amount => value
            data.append('smoking_amount', extractValue(dom, 'input#smoking_amount', 'value'));
            // * drug abuse: input[name=drug][checked] => value
            data.append('drug_abuse', extractValue(dom, 'input[name=drug][checked]', 'value'));
            // * drug detail: input#drug_detail => value
            data.append('drug_abuse_detail', extractValue(dom, 'input#drug_detail', 'value'));
            // * Allergy: input[name=Allergy][checked] => value
            data.append('allergy', extractValue(dom, 'input[name=Allergy][checked]', 'value'));
            // * Allergy detailt: input#Allergy_detail => value
            data.append('allergy_detail', extractValue(dom, 'input#Allergy_detail', 'value'));
            console.table(data);
            return data;

            // * no current medications: input#current_med => checked
            // * current medications: textarea#current_med_detail => innerText
            // * family: textarea#family => innerText
            // * personal social: textarea#personal_social => innerText
            // * General symtoms: textarea#gensym => innerText
            // * review head: input[name=review_head][checked] => value
            // * review head detail: textarea#review_detail_head => innerText
            // * review eye: input[name=review_eye][checked] => value
            // * review eye detail: textarea#review_detail_eye => innerText
            // * review cvs: input[name=review_cvs][checked] => value
            // * review cvs detail: textarea#review_detail_cvs => innerText
            // * review RS: input[name=review_rs][checked] => value
            // * review RD detail: textarea#review_detail_RS => innerText
            // * review GI: input[name=review_GI][checked] => value
            // * review GI detail: textarea#review_detail_GI => innerText
            // * review GU: input[name=review_GU][checked] => value
            // * review GU detail: textarea#review_detail_GU => innerText
            // * review Musculoskeletal system: input[name=review_musculo][checked] => value
            // * review Musculoskeletal system detail: textarea#review_detail_Musc => innerText
            // * review Nervous system: input[name=review_nerv][checked] => value
            // * review Nervous system detail: textarea#review_detail_nerv => innerText
            // * review Psychological sysmptoms: input[name=review_psych][checked] => value
            // * review Psychological sysmptoms detail: textarea#review_detail_psyc => innerText
            // * review other detail: textarea[name=review_other_text] => innerText
            // * NG tube/NG suction: input[name=require_NG] => checked
            // * Gastrostomy feeding: input[name=require_feeding] => checked
            // * Urinary cath. care: input[name=require_urinary] => checked
            // * Tracheostomy care: input[name=require_trache] => checked
            // * Hearing impairment: input[name=require_hearing] => checked
            // * Visiual impairment: input[name=require_visiual] => checked
            // * Isolation room: input[name=require_isolate] => checked
            // * Special Requiremen other: textarea[name=require_other_text] => innerText
            // * temperature: input#vital_T => value
            // * pulse: input#vital_P => value
            // * raspiry rate: input#vital_R => value
            // * SBP: input#vital_BP1 => value
            // * DBP: input#vital_BP2 => value
            // * height: input#height => value
            // * weight: input#weight => value
            // * BMI: input#BMI => value
            // * spo2: input#spo2 => value
            // * breathing: input[name=Room_type]=> value
            // * o2_type: input[name=via]=> value
            // * o2_rate: input#O2 => value
            // * level of conscious: input[name=conscious]=> value
            // * gcs_e: input#E => value
            // * gcs_v: input#V => value
            // * gcs_m: input#M => value
            // * gcs: input#glassgow_detail => value
            // * mental evaluation: input[name=mental]=> value
            // * orientation to time: input#orient_time => checked
            // * orientation to place: input#orient_place => checked
            // * orientation to person : input#orient_person => checked
            // * General appearance: textarea#general_app => innerText
            // * exam skin: input[name=exam_skin][checked] => value
            // * exam skin detail: textarea#exam_skin_detail => innerText
            // * exam head face: input[name=exam_face][checked] => value
            // * exam head face detail: textarea#exam_face_detail => innerText
            // * exam eye ent: input[name=exam_eye][checked] => value
            // * exam eye ent detail: textarea#exam_eye_detail => innerText
            // * exam neck: input[name=exam_neck][checked] => value
            // * exam neck detail: textarea#exam_neck_detail => innerText
            // * exam heart: input[name=exam_heart][checked] => value
            // * exam heart detail: textarea#exam_heart_detail => innerText
            // * exam lungs: input[name=exam_lungs][checked] => value
            // * exam lungs detail: textarea#exam_lungs_detail => innerText
            // * exam abdomen: input[name=exam_abdomen][checked] => value
            // * exam abdomen detail: textarea#exam_abdomen_detail => innerText
            // * exam Extremities: input[name=exam_extrem][checked] => value
            // * exam Extremities detail: textarea#exam_extremities_detail => innerText
            // * exam Nervous system: input[name=exam_nerv][checked] => value
            // * exam Nervous system detail: textarea#exam_nervous_detail => innerText
            // * exam Lymph nodes: input[name=exam_lymph][checked] => value
            // * exam Lymph nodes detail: textarea#exam_lymph_detail => innerText
            // * exam breasts: input[name=exam_breasts][checked] => value
            // * exam breasts detail: textarea#exam_breasts_detail => innerText
            // * exam genitalia: input[name=exam_genitalia][checked] => value
            // * exam genitalia detail: textarea#exam_genitalia_detail => innerText
            // * exam rectal: input[name=exam_rectal][checked] => value
            // * exam rectal detail: textarea#exam_rectal_detail => innerText
            // * Pertinent investigation: textarea#pertinent => innerText
            // * Problem list: textarea#problem => innerText
            // * Problem list continue: textarea#problem_cont => innerText
            // * discussion: textarea#discussion => innerText
            // * provisional_diagnosis: textarea#provisional_diag => innerText
            // * Plan of investigation: textarea#plan_invest => innerText
            // * Plan of management: textarea#plan_manage => innerText
            // * plan_special_group: input#plan_special_group => checked
            // * CPG_detail: input#CPG_detail => value
            // * Plan of consultation: textarea#plan_consult => innerText
            // * can estimate los: input[name=estimated] => checked
            // * estimated los: input#length_stay => value

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
