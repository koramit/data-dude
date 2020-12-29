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
        var item = null;
        var data = null;
        var dom = document.createElement("body");
        var feedback = document.getElementById('feedback');
        var deads = ['Natural', 'Accident', 'Suicide', 'Homicide', 'During investigations', 'Unspecified'];

        function runDudeSync(start, stop) {
            for (start; start < stop; start++) {
                feedback.innerHTML = start;
                duDudeSync('admit', start);
                duDudeSync('discharge', start);
            }
            feedback.innerHTML = 'done';
        }

        function duDudeSync(formName, id) {
            request.open('POST', `/call-dude/${formName}/${id}`, false);
            form = new FormData();
            form.append('_token', document.querySelector('input[name=_token]').value);
            request.send(form);
            if (request.status !== 200) {
                return;
            }
            dom.innerHTML = request.responseText;
            form = formName === 'admit' ? extractDudeAdmit() : extractDudeDischarge();
            request.open('POST', `/dudes/${formName}/${id}`, false);
            request.send(form);
        }

        function extractDudeAdmit() {
            data = new FormData();

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
            // * no current medications: input#current_med => checked
            data.append('no_current_medications', extractValue(dom, 'input#current_med', 'checked'));
            // * current medications: textarea#current_med_detail => innerText
            data.append('current_med_detail', extractValue(dom, '#current_med_detail', 'innerText'));
            // * family: textarea#family => innerText
            data.append('family', extractValue(dom, '#family', 'innerText'));
            // * personal social: textarea#personal_social => innerText
            data.append('personal_social', extractValue(dom, '#personal_social', 'innerText'));
            // * General symtoms: textarea#gensym => innerText
            data.append('general_symtoms', extractValue(dom, '#gensym', 'innerText'));
            // * review head: input[name=review_head][checked] => value
            data.append('review_head', extractValue(dom, 'input[name=review_head][checked]', 'value'));
            // * review head detail: textarea#review_detail_head => innerText
            data.append('review_detail_head', extractValue(dom, '#review_detail_head', 'innerText'));
            // * review eye: input[name=review_eye][checked] => value
            data.append('review_eye_ENT', extractValue(dom, 'input[name=review_eye][checked]', 'value'));
            // * review eye detail: textarea#review_detail_eye => innerText
            data.append('review_detail_eye_ENT', extractValue(dom, '#review_detail_eye', 'innerText'));
            // * review cvs: input[name=review_cvs][checked] => value
            data.append('review_CVS', extractValue(dom, 'input[name=review_cvs][checked]', 'value'));
            // * review cvs detail: textarea#review_detail_cvs => innerText
            data.append('review_detail_CVS', extractValue(dom, '#review_detail_cvs', 'innerText'));
            // * review RS: input[name=review_rs][checked] => value
            data.append('review_RS', extractValue(dom, 'input[name=review_rs][checked]', 'value'));
            // * review RD detail: textarea#review_detail_RS => innerText
            data.append('review_detail_RS', extractValue(dom, '#review_detail_RS', 'innerText'));
            // * review GI: input[name=review_GI][checked] => value
            data.append('review_GI', extractValue(dom, 'input[name=review_GI][checked]', 'value'));
            // * review GI detail: textarea#review_detail_GI => innerText
            data.append('review_detail_GI', extractValue(dom, '#review_detail_GI', 'innerText'));
            // * review GU: input[name=review_GU][checked] => value
            data.append('review_GU', extractValue(dom, 'input[name=review_GU][checked]', 'value'));
            // * review GU detail: textarea#review_detail_GU => innerText
            data.append('review_detail_GU', extractValue(dom, '#review_detail_GU', 'innerText'));
            // * review Musculoskeletal system: input[name=review_musculo][checked] => value
            data.append('review_musculoskeletal_system', extractValue(dom, 'input[name=review_musculo][checked]',
                'value'));
            // * review Musculoskeletal system detail: textarea#review_detail_Musc => innerText
            data.append('review_detail_musculoskeletal_system', extractValue(dom, '#review_detail_Musc', 'innerText'));
            // * review Nervous system: input[name=review_nerv][checked] => value
            data.append('review_nervous_system', extractValue(dom, 'input[name=review_nerv][checked]', 'value'));
            // * review Nervous system detail: textarea#review_detail_nerv => innerText
            data.append('review_detail_nervous_system', extractValue(dom, '#review_detail_nerv', 'innerText'));
            // * review Psychological sysmptoms: input[name=review_psych][checked] => value
            data.append('review_psychological_sysmptoms', extractValue(dom, 'input[name=review_psych][checked]',
                'value'));
            // * review Psychological sysmptoms detail: textarea#review_detail_psyc => innerText
            data.append('review_detail_psychological_sysmptoms', extractValue(dom, '#review_detail_psyc', 'innerText'));
            // * review other detail: textarea[name=review_other_text] => innerText
            data.append('review_other', extractValue(dom, 'textarea[name=review_other_text]', 'innerText'));
            // * NG tube/NG suction: input[name=require_NG] => checked
            data.append('require_NG_tube_NG_suction', extractValue(dom, 'input[name=require_NG]', 'checked'));
            // * Gastrostomy feeding: input[name=require_feeding] => checked
            data.append('require_gastrostomy_feeding', extractValue(dom, 'input[name=require_feeding]', 'checked'));
            // * Urinary cath. care: input[name=require_urinary] => checked
            data.append('require_urinary_cath_care', extractValue(dom, 'input[name=require_urinary]', 'checked'));
            // * Tracheostomy care: input[name=require_trache] => checked
            data.append('require_tracheostomy_care', extractValue(dom, 'input[name=require_trache]', 'checked'));
            // * Hearing impairment: input[name=require_hearing] => checked
            data.append('require_hearing_impairment', extractValue(dom, 'input[name=require_hearing]', 'checked'));
            // * Visiual impairment: input[name=require_visiual] => checked
            data.append('require_visiual_impairment', extractValue(dom, 'input[name=require_visiual]', 'checked'));
            // * Isolation room: input[name=require_isolate] => checked
            data.append('require_isolation_room', extractValue(dom, 'input[name=require_isolate]', 'checked'));
            // * Special Requiremen other: textarea[name=require_other_text] => innerText
            data.append('require_isolation_room', extractValue(dom, 'textarea[name=require_other_text]', 'innerText'));
            // * temperature: input#vital_T => value
            data.append('temperature', extractValue(dom, '#vital_T', 'value'));
            // * pulse: input#vital_P => value
            data.append('pulse', extractValue(dom, '#vital_P', 'value'));
            // * raspiry rate: input#vital_R => value
            data.append('raspiry rate', extractValue(dom, '#vital_R', 'value'));
            // * SBP: input#vital_BP1 => value
            data.append('SBP', extractValue(dom, '#vital_BP1', 'value'));
            // * DBP: input#vital_BP2 => value
            data.append('DBP', extractValue(dom, '#vital_BP2', 'value'));
            // * height: input#height => value
            data.append('height', extractValue(dom, '#height', 'value'));
            // * weight: input#weight => value
            data.append('weight', extractValue(dom, '#weight', 'value'));
            // * BMI: input#BMI => value
            data.append('BMI', extractValue(dom, '#BMI', 'value'));
            // * spo2: input#spo2 => value
            data.append('SPO2', extractValue(dom, '#spo2', 'value'));
            // * breathing: input[name=Room_type]=> value
            data.append('breathing', extractValue(dom, 'input[name=Room_type]', 'value'));
            // * o2_type: input[name=via]=> value
            data.append('O2_type', extractValue(dom, 'input[name=via]', 'value'));
            // * o2_rate: input#O2 => value
            data.append('O2_rate', extractValue(dom, '#O2', 'value'));
            // * level of conscious: input[name=conscious]=> value
            data.append('level_of_conscious', extractValue(dom, 'input[name=conscious]', 'value'));
            // * gcs_e: input#E => value
            data.append('GCS_E', extractValue(dom, '#E', 'value'));
            // * gcs_v: input#V => value
            data.append('GCS_V', extractValue(dom, '#V', 'value'));
            // * gcs_m: input#M => value
            data.append('GCS_M', extractValue(dom, '#M', 'value'));
            // * gcs: input#glassgow_detail => value
            data.append('glassgow_detail', extractValue(dom, '#glassgow_detail', 'value'));
            // * mental evaluation: input[name=mental]=> value
            data.append('mental_evaluation', extractValue(dom, 'input[name=mental]', 'value'));
            // * orientation to time: input#orient_time => checked
            data.append('orientation_to_time', extractValue(dom, '#orient_time', 'value'));
            // * orientation to place: input#orient_place => checked
            data.append('orientation_to_place', extractValue(dom, '#orient_place', 'value'));
            // * orientation to person : input#orient_person => checked
            data.append('orientation_to_person', extractValue(dom, '#orient_person', 'value'));
            // * General appearance: textarea#general_app => innerText
            data.append('general_appearance', extractValue(dom, '#general_app', 'innerText'));
            // * exam skin: input[name=exam_skin][checked] => value
            data.append('exam_skin', extractValue(dom, 'input[name=exam_skin][checked]', 'value'));
            // * exam skin detail: textarea#exam_skin_detail => innerText
            data.append('exam_skin_detail', extractValue(dom, '#exam_skin_detail', 'innerText'));
            // * exam head face: input[name=exam_face][checked] => value
            data.append('exam_head_face', extractValue(dom, 'input[name=exam_face][checked]', 'value'));
            // * exam head face detail: textarea#exam_face_detail => innerText
            data.append('exam_head_face_detail', extractValue(dom, '#exam_face_detail', 'innerText'));
            // * exam eye ent: input[name=exam_eye][checked] => value
            data.append('exam_eye_ENT', extractValue(dom, 'input[name=exam_eye][checked]', 'value'));
            // * exam eye ent detail: textarea#exam_eye_detail => innerText
            data.append('exam_eye_ENT_detail', extractValue(dom, '#exam_eye_detail', 'innerText'));
            // * exam neck: input[name=exam_neck][checked] => value
            data.append('exam_neck', extractValue(dom, 'input[name=exam_neck][checked]', 'value'));
            // * exam neck detail: textarea#exam_neck_detail => innerText
            data.append('exam_neck_detail', extractValue(dom, '#exam_neck_detail', 'innerText'));
            // * exam heart: input[name=exam_heart][checked] => value
            data.append('exam_heart', extractValue(dom, 'input[name=exam_heart][checked]', 'value'));
            // * exam heart detail: textarea#exam_heart_detail => innerText
            data.append('exam_heart_detail', extractValue(dom, '#exam_heart_detail', 'innerText'));
            // * exam lungs: input[name=exam_lungs][checked] => value
            data.append('exam_lungs', extractValue(dom, 'input[name=exam_lungs][checked]', 'value'));
            // * exam lungs detail: textarea#exam_lungs_detail => innerText
            data.append('exam_lungs_detail', extractValue(dom, '#exam_lungs_detail', 'innerText'));
            // * exam abdomen: input[name=exam_abdomen][checked] => value
            data.append('exam_abdomen', extractValue(dom, 'input[name=exam_abdomen][checked]', 'value'));
            // * exam abdomen detail: textarea#exam_abdomen_detail => innerText
            data.append('exam_abdomen_detail', extractValue(dom, '#exam_abdomen_detail', 'innerText'));
            // * exam Extremities: input[name=exam_extrem][checked] => value
            data.append('exam_extremities', extractValue(dom, 'input[name=exam_extrem][checked]', 'value'));
            // * exam Extremities detail: textarea#exam_extremities_detail => innerText
            data.append('exam_extremities_detail', extractValue(dom, '#exam_extremities_detail', 'innerText'));
            // * exam Nervous system: input[name=exam_nerv][checked] => value
            data.append('exam_nervous_system', extractValue(dom, 'input[name=exam_nerv][checked]', 'value'));
            // * exam Nervous system detail: textarea#exam_nervous_detail => innerText
            data.append('exam_nervous_system_detail', extractValue(dom, '#exam_nervous_detail', 'innerText'));
            // * exam Lymph nodes: input[name=exam_lymph][checked] => value
            data.append('exam_lymph_nodes', extractValue(dom, 'input[name=exam_lymph][checked]', 'value'));
            // * exam Lymph nodes detail: textarea#exam_lymph_detail => innerText
            data.append('exam_lymph_nodes_detail', extractValue(dom, '#exam_lymph_detail', 'innerText'));
            // * exam breasts: input[name=exam_breasts][checked] => value
            data.append('exam_breasts', extractValue(dom, 'input[name=exam_breasts][checked]', 'value'));
            // * exam breasts detail: textarea#exam_breasts_detail => innerText
            data.append('exam_breasts_detail', extractValue(dom, '#exam_breasts_detail', 'innerText'));
            // * exam genitalia: input[name=exam_genitalia][checked] => value
            data.append('exam_genitalia', extractValue(dom, 'input[name=exam_genitalia][checked]', 'value'));
            // * exam genitalia detail: textarea#exam_genitalia_detail => innerText
            data.append('exam_genitalia_detail', extractValue(dom, '#exam_genitalia_detail', 'innerText'));
            // * exam rectal: input[name=exam_rectal][checked] => value
            data.append('exam_rectal', extractValue(dom, 'input[name=exam_rectal][checked]', 'value'));
            // * exam rectal detail: textarea#exam_rectal_detail => innerText
            data.append('exam_rectal_detail', extractValue(dom, '#exam_rectal_detail', 'innerText'));
            // * Pertinent investigation: textarea#pertinent => innerText
            data.append('pertinent_investigation', extractValue(dom, '#pertinent', 'innerText'));
            // * Problem list: textarea#problem => innerText
            data.append('problem_list', extractValue(dom, '#problem', 'innerText'));
            // * Problem list continue: textarea#problem_cont => innerText
            data.append('problem_list_continue', extractValue(dom, '#problem_cont', 'innerText'));
            // * discussion: textarea#discussion => innerText
            data.append('discussion', extractValue(dom, '#discussion', 'innerText'));
            // * provisional_diagnosis: textarea#provisional_diag => innerText
            data.append('provisional_diagnosis', extractValue(dom, '#provisional_diag', 'innerText'));
            // * Plan of investigation: textarea#plan_invest => innerText
            data.append('plan_investigation', extractValue(dom, '#plan_invest', 'innerText'));
            // * Plan of management: textarea#plan_manage => innerText
            data.append('plan_management', extractValue(dom, '#plan_manage', 'innerText'));
            // * plan_special_group: input#plan_special_group => checked
            data.append('plan_special_group', extractValue(dom, '#plan_special_group', 'checked'));
            // * CPG_detail: input#CPG_detail => value
            data.append('CPG_detail', extractValue(dom, '#CPG_detail', 'value'));
            // * Plan of consultation: textarea#plan_consult => innerText
            data.append('plan_consultation', extractValue(dom, '#plan_consult', 'innerText'));
            // * can estimate los: input[name=estimated] => checked
            data.append('can_estimate_los', extractValue(dom, 'input[name=estimated]', 'checked'));
            // * estimated los: input#length_stay => value
            data.append('estimated los', extractValue(dom, '#length_stay', 'value'));
            return data;
        }

        function extractDudeDischarge() {
            data = new FormData();
            item = null;

            data.append('an', extractValue(dom, '#AN', 'value'));
            data.append('hn', extractValue(dom, '#HN', 'value'));
            data.append('patient_name', extractValue(dom, '#pname', 'value'));
            data.append('patient_age', extractValue(dom, '#Age', 'value'));
            data.append('encountered_at', extractValue(dom, '#Admit_Date', 'value'));
            data.append('discharged_at', extractValue(dom, '#Discharge_Date', 'value'));
            data.append('length_of_stay', extractValue(dom, '#inhospital', 'value'));

            // ward
            data.append('ward', extractValue(dom, '#MD_Ward', 'innerText', true));

            // attending
            item = extractValue(dom, '#AttendingStaff', 'innerHTML', true);
            if (item) {
                data.append('attending_staff', item.split(' | ')[0]);
                data.append('attending_staff_division', item.split(' | ')[1]);
            } else {
                data.append('attending_staff', null);
                data.append('attending_staff_division', null);
            }

            // author
            item = extractValue(dom, '#Resident', 'innerHTML', true);
            if (item) {
                data.append('author', item.split(' | ')[1]);
                data.append('author_pln', item.split(' | ')[0]);
            } else {
                data.append('author', null);
                data.append('author_pln', null);
            }

            // division
            let division = extractValue(dom, 'select[name=division1]', 'innerText', true);
            data.append('primary_division', division ? division.split(' | ')[1] : null);
            division = extractValue(dom, 'select[name=division2]', 'innerText', true);
            data.append('secondary_division', division ? division.split(' | ')[1] : null);

            data.append('principal_diagnosis', extractValue(dom, '#PrincipalDx', 'innerText'));
            data.append('admit_reason', extractValue(dom, '#ReasonForAdmission', 'innerText'));
            data.append('comorbids', extractValue(dom, '#Comorbids', 'innerText'));
            data.append('complications', extractValue(dom, '#Complication', 'innerText'));
            data.append('external_cause', extractValue(dom, '#Extcause', 'innerText'));
            data.append('other_diagnosis', extractValue(dom, '#OtherDiag', 'innerText'));
            data.append('OR_procedures', extractValue(dom, '#OrProcedure', 'innerText'));
            data.append('non_OR_Procedures', extractValue(dom, '#NonOrProcedure', 'innerText'));
            data.append('chief_complaint', extractValue(dom, '#CheifComplaint', 'innerText'));
            data.append('significant_findings', extractValue(dom, '#SignFinding', 'innerText'));
            data.append('significant_procedures', extractValue(dom, '#SignProcedure', 'innerText'));
            data.append('hospital_course', extractValue(dom, '#Hosp', 'innerText'));
            data.append('condition_upon_discharge', extractValue(dom, '#DC', 'innerText'));
            data.append('follow_up_instruction', extractValue(dom, '#FollowInstruction', 'innerText'));

            // discharge
            let discharge = extractValue(dom, '#ds', 'innerText', true);
            data.append('discharge_status', discharge || null);
            discharge = extractValue(dom, '#dt', 'innerText', true);
            data.append('discharge_type', discharge || null);

            data.append('significant_medications', extractValue(dom, 'WardRx', 'innerText'));
            data.append('home_medications', extractValue(dom, 'HomeRx', 'innerText'));
            data.append('interesting_case', extractValue(dom, '#InterestingCase', 'checked'));
            data.append('completed', extractValue(dom, '#Complete', 'checked'));

            if (data.discharge_status === 'DEAD') {
                data.append('dead_report_charecter_of_death', extractValue(dom, 'input[name=COD][checked]', 'value'));
                if (data.dead_report_charecter_of_death) {
                    data.append('dead_report_charecter_of_death', deads[parseInt(data.dead_report_charecter_of_death) -
                        1]);
                }
                data.append('dead_report_cause_of_dead_a', extractValue(dom, '#LeadingtoDeath', 'innerText'));
                data.append('dead_report_cause_of_dead_b', extractValue(dom, '#Dueto1', 'innerText'));
                data.append('dead_report_cause_of_dead_c', extractValue(dom, '#Dueto2', 'innerText'));
                data.append('dead_report_cause_of_dead_d', extractValue(dom, '#Dueto3', 'innerText'));
                data.append('dead_report_other_significant_conditions', extractValue(dom, '#OtherSign', 'innerText'));
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
