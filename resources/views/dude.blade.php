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
            if (document.querySelector('input#AN')) {
                console.log('input#AN');
            } else {
                console.log('ERROR : input#AN');
            }

            // * HN: value => input#HN
            if (document.querySelector('input#HN')) {
                console.log('input#HN');
            } else {
                console.log('ERROR : input#HN');
            }

            // * pname: value => input#pname
            if (document.querySelector('input#pname')) {
                console.log('input#pname');
            } else {
                console.log('ERROR : input#pname');
            }

            // * gender: value => input[name=sex]
            if (document.querySelector('input[name=sex]')) {
                console.log('input[name=sex]');
            } else {
                console.log('ERROR : input[name=sex]');
            }

            // * Age: value => input#Age
            if (document.querySelector('input#Age')) {
                console.log('input#Age');
            } else {
                console.log('ERROR : input#Age');
            }

            // * attending: option => select[name=staff]
            if (document.querySelector('select[name=staff]')) {
                console.log('select[name=staff]');
            } else {
                console.log('ERROR : select[name=staff]');
            }

            // * author_pln: value => input[name=dent_code]
            if (document.querySelector('input[name=dent_code]')) {
                console.log('input[name=dent_code]');
            } else {
                console.log('ERROR : input[name=dent_code]');
            }

            // * author_title: value => input[name=dent_pos]
            if (document.querySelector('input[name=dent_pos]')) {
                console.log('input[name=dent_pos]');
            } else {
                console.log('ERROR : input[name=dent_pos]');
            }

            // * author_fname: value => input[name=dent_name]
            if (document.querySelector('input[name=dent_name]')) {
                console.log('input[name=dent_name]');
            } else {
                console.log('ERROR : input[name=dent_name]');
            }

            // * author_lname: value => input[name=dent_sure]
            if (document.querySelector('input[name=dent_sure]')) {
                console.log('input[name=dent_sure]');
            } else {
                console.log('ERROR : input[name=dent_sure]');
            }

            // * date_admit: value => input[name=Admit_Date]
            if (document.querySelector('input[name=Admit_Date]')) {
                console.log('input[name=Admit_Date]');
            } else {
                console.log('ERROR : input[name=Admit_Date]');
            }

            // * Chief complaint: textarea #CC => innerText
            if (dom.querySelector('#CC')) {
                console.log('Chief complaint');
            } else {
                console.log('ERROR : Chief complaint')
            }

            // * Admit reason: input[name = reason][checked] => value
            if (dom.querySelector('input[name=reason]')) {
                console.log('Admit reason');
            } else {
                console.log('ERROR : Admit reason')
            }

            // * History of present illness: textarea #present => innerText
            if (dom.querySelector('#present')) {
                console.log('History of present illness');
            } else {
                console.log('ERROR : History of present illness')
            }

            // * Past history: textarea #past => innerText
            if (dom.querySelector('#past')) {
                console.log('Past history');
            } else {
                console.log('ERROR : Past history')
            }

            // * Women: input #forwomen => checked
            if (dom.querySelector('#forwomen')) {
                console.log('Women');
            } else {
                console.log('ERROR : Women')
            }

            // * Pregnancy: input[name = women][checked] => value
            if (dom.querySelector('input[name=women]')) {
                console.log('Pregnancy');
            } else {
                console.log('ERROR : Pregnancy')
            }

            // * Pregnant week: input #Pregnant_week => value
            if (dom.querySelector('#Pregnant_week')) {
                console.log('Pregnant week');
            } else {
                console.log('ERROR : Pregnant week')
            }

            // * DM: input[name = DM][checked] => value
            if (dom.querySelector('input[name=DM]')) {
                console.log('DM');
            } else {
                console.log('ERROR : DM')
            }

            // * DM type: input[name = DM_Type][checked] => value
            if (dom.querySelector('input[name=DM_Type]')) {
                console.log('DM type');
            } else {
                console.log('ERROR : DM type')
            }

            // * DM complication DR: input #DR => checked
            if (dom.querySelector('input#DR')) {
                console.log('DM complication');
            } else {
                console.log('ERROR : DM complication')
            }

            // * DM complication Nephropathy: input#Nephro => checked
            if (dom.querySelector('input#Nephro')) {
                console.log('DM complication Nephropathy');
            } else {
                console.log('ERROR : DM complication Nephropathy')
            }

            // * DM complication Neuropathy: input#Neuro => checked
            if (dom.querySelector('input#Neuro')) {
                console.log('DM complication Neuropathy');
            } else {
                console.log('ERROR : DM complication Neuropathy')
            }

            // * DM treatment diet: input#diet => checked
            if (dom.querySelector('input#diet')) {
                console.log('DM treatment diet');
            } else {
                console.log('ERROR : DM treatment diet')
            }

            // * DM treatment oral medications: input#oral => checked
            if (dom.querySelector('input#oral')) {
                console.log('DM treatment oral medications');
            } else {
                console.log('ERROR : DM treatment oral medications')
            }

            // * DM treatment insulin: input#insulin => checked
            if (dom.querySelector('input#insulin')) {
                console.log('DM treatment insulin');
            } else {
                console.log('ERROR : DM treatment insulin')
            }

            // * Hypertension: input[name=Hypertension][checked] => value
            if (dom.querySelector('input[name=Hypertension]')) {
                console.log('Hypertension');
            } else {
                console.log('ERROR : Hypertension')
            }

            // * Coronary artery disease: input[name=Coronary][checked] => value
            if (dom.querySelector('input[name=Coronary]')) {
                console.log('Coronary artery disease');
            } else {
                console.log('ERROR : Coronary artery disease')
            }

            // * Valvular heart disease: input[name=Valvular][checked] => value
            if (dom.querySelector('input[name=Valvular]')) {
                console.log('Valvular heart disease');
            } else {
                console.log('ERROR : Valvular heart disease')
            }

            // * Stroke: input[name=Stroke][checked] => value
            if (dom.querySelector('input[name=Stroke]')) {
                console.log('Stroke');
            } else {
                console.log('ERROR : Stroke')
            }

            // * COPD: input[name=COPD][checked] => value
            if (dom.querySelector('input[name=COPD]')) {
                console.log('COPD');
            } else {
                console.log('ERROR : COPD')
            }

            // * asthma: input[name=asthma][checked] => value
            if (dom.querySelector('input[name=asthma]')) {
                console.log('asthma');
            } else {
                console.log('ERROR : asthma')
            }

            // * Chronic kidney disease: input[name= kidney][checked] => value
            if (dom.querySelector('input[name=kidney]')) {
                console.log('Chronic kidney disease');
            } else {
                console.log('ERROR : Chronic kidney disease')
            }

            // * Hyperlipidemia: input[name=Dyslipidemia][checked] => value
            if (dom.querySelector('input[name=Dyslipidemia]')) {
                console.log('Hyperlipidemia');
            } else {
                console.log('ERROR : Hyperlipidemia')
            }

            // * Cirrhosis: input[name=Cirrhosis][checked] => value
            if (dom.querySelector('input[name=Cirrhosis]')) {
                console.log('Cirrhosis');
            } else {
                console.log('ERROR : Cirrhosis')
            }

            // * HIV: input[name=HIV][checked] => value
            if (dom.querySelector('input[name=HIV]')) {
                console.log('HIV');
            } else {
                console.log('ERROR : HIV')
            }

            // * AIDS: input[name=AIDS][checked] => value
            if (dom.querySelector('input[name=AIDS]')) {
                console.log('AIDS');
            } else {
                console.log('ERROR : AIDS')
            }

            // * Epilepsy: input[name=Epilepsy][checked] => value
            if (dom.querySelector('input[name=Epilepsy]')) {
                console.log('Epilepsy');
            } else {
                console.log('ERROR : Epilepsy')
            }

            // * Coagulopathy: input[name=Coagulopathy][checked] => value
            if (dom.querySelector('input[name=Coagulopathy]')) {
                console.log('Coagulopathy');
            } else {
                console.log('ERROR : Coagulopathy')
            }

            // * HBV infection: input[name=ABV][checked] => value
            if (dom.querySelector('input[name=ABV]')) {
                console.log('HBV');
            } else {
                console.log('ERROR : HBV')
            }

            // * HCV infection: input[name=ACV][checked] => value
            if (dom.querySelector('input[name=ACV]')) {
                console.log('HCV');
            } else {
                console.log('ERROR : HCV')
            }

            // * Cancer: input[name=Cancer][checked] => value
            if (dom.querySelector('input[name=Cancer]')) {
                console.log('Cancer');
            } else {
                console.log('ERROR : Cancer')
            }

            // * Cancer organs: input[name=Cancer_Detail] => value
            if (dom.querySelector('input[name=Cancer_Detail]')) {
                console.log('Cancer_Detail');
            } else {
                console.log('ERROR : Cancer_Detail')
            }

            // * Leukemia: input[name=Leukemia][checked] => value
            if (dom.querySelector('input[name=Leukemia]')) {
                console.log('Leukemia');
            } else {
                console.log('ERROR : Leukemia')
            }

            // * Lymphoma: input[name=Lymphoma][checked] => value
            if (dom.querySelector('input[name=Lymphoma]')) {
                console.log('Lymphoma');
            } else {
                console.log('ERROR : Lymphoma')
            }

            // * Pacemaker implant: input[name=Pacemaker][checked] => value
            if (dom.querySelector('input[name=Pacemaker]')) {
                console.log('Pacemaker');
            } else {
                console.log('ERROR : Pacemaker')
            }

            // * Chronic arthritis: input[name=Chronic][checked] => value
            if (dom.querySelector('input[name=Chronic]')) {
                console.log('Chronic arthritis');
            } else {
                console.log('ERROR : Chronic arthritis')
            }

            // * SLE: input[name=SLE][checked] => value
            if (dom.querySelector('input[name=SLE]')) {
                console.log('SLE');
            } else {
                console.log('ERROR : SLE')
            }

            // * Other autoimmune: input[name=autoimmune][checked] => value
            if (dom.querySelector('input[name=autoimmune]')) {
                console.log('autoimmune');
            } else {
                console.log('ERROR : autoimmune')
            }

            // * TB or other active communicable disease: input[name=TB][checked] => value
            if (dom.querySelector('input[name=TB]')) {
                console.log('TB');
            } else {
                console.log('ERROR : TB')
            }

            // * Dementia: input[name=Dementia][checked] => value
            if (dom.querySelector('input[name=Dementia]')) {
                console.log('Dementia');
            } else {
                console.log('ERROR : Dementia')
            }

            // * Psychiatric illness: input[name=Phychiartic][checked] => value
            if (dom.querySelector('input[name=Phychiartic]')) {
                console.log('Phychiartic');
            } else {
                console.log('ERROR : Phychiartic')
            }

            // * Other comorbids: input[name=Others_Comorbid] => value
            if (dom.querySelector('input[name=Others_Comorbid]')) {
                console.log('Others_Comorbid');
            } else {
                console.log('ERROR : Others_Comorbid')
            }

            // * alcohol: input[name=alcohol][checked] => value
            if (dom.querySelector('input[name=alcohol]')) {
                console.log('alcohol');
            } else {
                console.log('ERROR : alcohol')
            }

            // * alcohol amount: input#alcohol_amount => value
            if (dom.querySelector('#alcohol_amount')) {
                console.log('alcohol_amount');
            } else {
                console.log('ERROR : alcohol_amount');
            }

            // * smoking: input[name=smoking][checked] => value
            if (dom.querySelector('input[name=smoking]')) {
                console.log('smoking');
            } else {
                console.log('ERROR : smoking')
            }

            // * smoking amount: input#smoking_amount => value
            if (dom.querySelector('#smoking_amount')) {
                console.log('smoking_amount');
            } else {
                console.log('ERROR : smoking_amount');
            }

            // * drug abuse: input[name=drug][checked] => value
            if (dom.querySelector('input[name=drug]')) {
                console.log('drug');
            } else {
                console.log('ERROR : drug')
            }

            // * drug detail: input#drug_detail => value
            if (dom.querySelector('#drug_detail')) {
                console.log('drug_detail');
            } else {
                console.log('ERROR : drug_detail');
            }

            // * Allergy: input[name=Allergy][checked] => value
            if (dom.querySelector('input[name=Allergy]')) {
                console.log('Allergy');
            } else {
                console.log('ERROR : Allergy')
            }

            // * Allergy detailt: input#Allergy_detail => value
            if (dom.querySelector('#Allergy_detail')) {
                console.log('Allergy_detail');
            } else {
                console.log('ERROR : Allergy_detail');
            }

            // * no current medications: input#current_med => checked
            if (dom.querySelector('#current_med')) {
                console.log('current_med');
            } else {
                console.log('ERROR : current_med');
            }

            // * current medications: textarea#current_med_detail => innerText
            if (dom.querySelector('#current_med_detail')) {
                console.log('current_med_detail');
            } else {
                console.log('ERROR : current_med_detail');
            }

            // * family: textarea#family => innerText
            if (dom.querySelector('#family')) {
                console.log('family');
            } else {
                console.log('ERROR : family');
            }

            // * personal social: textarea#personal_social => innerText
            if (dom.querySelector('#personal_social')) {
                console.log('personal_social');
            } else {
                console.log('ERROR : personal_social');
            }

            // * General symtoms: textarea#gensym => innerText
            if (dom.querySelector('#gensym')) {
                console.log('gensym');
            } else {
                console.log('ERROR : gensym');
            }

            // * review head: input[name=review_head][checked] => value
            if (dom.querySelector('input[name=review_head]')) {
                console.log('review_head');
            } else {
                console.log('ERROR : review_head');
            }

            // * review head detail: textarea#review_detail_head => innerText
            if (dom.querySelector('#review_detail_head')) {
                console.log('review_detail_head');
            } else {
                console.log('ERROR : review_detail_head');
            }

            // * review eye: input[name=review_eye][checked] => value
            if (dom.querySelector('input[name=review_eye]')) {
                console.log('review_eye');
            } else {
                console.log('ERROR : review_eye');
            }

            // * review eye detail: textarea#review_detail_eye => innerText
            if (dom.querySelector('#review_detail_eye')) {
                console.log('review_detail_eye');
            } else {
                console.log('ERROR : review_detail_eye');
            }

            // * review cvs: input[name=review_cvs][checked] => value
            if (dom.querySelector('input[name=review_cvs]')) {
                console.log('review_cvs');
            } else {
                console.log('ERROR : review_cvs');
            }

            // * review cvs detail: textarea#review_detail_cvs => innerText
            if (dom.querySelector('#review_detail_cvs')) {
                console.log('review_detail_cvs');
            } else {
                console.log('ERROR : review_detail_cvs');
            }

            // * review RS: input[name=review_rs][checked] => value
            if (dom.querySelector('input[name=review_rs]')) {
                console.log('review_rs');
            } else {
                console.log('ERROR : review_rs');
            }

            // * review RD detail: textarea#review_detail_RS => innerText
            if (dom.querySelector('#review_detail_RS')) {
                console.log('review_detail_RS');
            } else {
                console.log('ERROR : review_detail_RS');
            }

            // * review GI: input[name=review_GI][checked] => value
            if (dom.querySelector('input[name=review_GI]')) {
                console.log('review_GI');
            } else {
                console.log('ERROR : review_GI');
            }

            // * review GI detail: textarea#review_detail_GI => innerText
            if (dom.querySelector('#review_detail_GI')) {
                console.log('review_detail_GI');
            } else {
                console.log('ERROR : review_detail_GI');
            }

            // * review GU: input[name=review_GU][checked] => value
            if (dom.querySelector('input[name=review_GU]')) {
                console.log('review_GU');
            } else {
                console.log('ERROR : review_GU');
            }

            // * review GU detail: textarea#review_detail_GU => innerText
            if (dom.querySelector('#review_detail_GU')) {
                console.log('review_detail_GU');
            } else {
                console.log('ERROR : review_detail_GU');
            }

            // * review Musculoskeletal system: input[name=review_musculo][checked] => value
            if (dom.querySelector('input[name=review_musculo]')) {
                console.log('review_musculo');
            } else {
                console.log('ERROR : review_musculo');
            }

            // * review Musculoskeletal system detail: textarea#review_detail_Musc => innerText
            if (dom.querySelector('#review_detail_Musc')) {
                console.log('review_detail_Musc');
            } else {
                console.log('ERROR : review_detail_Musc');
            }

            // * review Nervous system: input[name=review_nerv][checked] => value
            if (dom.querySelector('input[name=review_nerv]')) {
                console.log('review_nerv');
            } else {
                console.log('ERROR : review_nerv');
            }

            // * review Nervous system detail: textarea#review_detail_nerv => innerText
            if (dom.querySelector('#review_detail_nerv')) {
                console.log('review_detail_nerv');
            } else {
                console.log('ERROR : review_detail_nerv');
            }

            // * review Psychological sysmptoms: input[name=review_psych][checked] => value
            if (dom.querySelector('input[name=review_psych]')) {
                console.log('review_psych');
            } else {
                console.log('ERROR : review_psych');
            }

            // * review Psychological sysmptoms detail: textarea#review_detail_psyc => innerText
            if (dom.querySelector('#review_detail_psyc')) {
                console.log('review_detail_psyc');
            } else {
                console.log('ERROR : review_detail_psyc');
            }

            // * review other detail: textarea[name=review_other_text] => innerText
            if (dom.querySelector('input[name=review_other_text]')) {
                console.log('review_other_text');
            } else {
                console.log('ERROR : review_other_text');
            }

            // * NG tube/NG suction: input[name=require_NG] => checked
            if (dom.querySelector('input[name=require_NG]')) {
                console.log('require_NG');
            } else {
                console.log('ERROR : require_NG');
            }

            // * Gastrostomy feeding: input[name=require_feeding] => checked
            if (dom.querySelector('input[name=require_feeding]')) {
                console.log('require_feeding');
            } else {
                console.log('ERROR : require_feeding');
            }

            // * Urinary cath. care: input[name=require_urinary] => checked
            if (dom.querySelector('input[name=require_urinary]')) {
                console.log('require_urinary');
            } else {
                console.log('ERROR : require_urinary');
            }

            // * Tracheostomy care: input[name=require_trache] => checked
            if (dom.querySelector('input[name=require_trache]')) {
                console.log('require_trache');
            } else {
                console.log('ERROR : require_trache');
            }

            // * Hearing impairment: input[name=require_hearing] => checked
            if (dom.querySelector('input[name=require_hearing]')) {
                console.log('require_hearing');
            } else {
                console.log('ERROR : require_hearing');
            }

            // * Visiual impairment: input[name=require_visiual] => checked
            if (dom.querySelector('input[name=require_visiual]')) {
                console.log('require_visiual');
            } else {
                console.log('ERROR : require_visiual');
            }

            // * Isolation room: input[name=require_isolate] => checked
            if (dom.querySelector('input[name=require_isolate]')) {
                console.log('require_isolate');
            } else {
                console.log('ERROR : require_isolate');
            }

            // * Special Requiremen other: textarea[name=require_other_text] => innerText
            if (dom.querySelector('textarea[name=require_other_text]')) {
                console.log('require_other_text');
            } else {
                console.log('ERROR : require_other_text');
            }

            // * temperature: input#vital_T => value
            if (dom.querySelector('#vital_T')) {
                console.log('vital_T');
            } else {
                console.log('ERROR : vital_T');
            }

            // * pulse: input#vital_P => value
            if (dom.querySelector('#vital_P')) {
                console.log('vital_P');
            } else {
                console.log('ERROR : vital_P');
            }

            // * raspiry rate: input#vital_R => value
            if (dom.querySelector('#vital_R')) {
                console.log('vital_R');
            } else {
                console.log('ERROR : vital_R');
            }

            // * SBP: input#vital_BP1 => value
            if (dom.querySelector('#vital_BP1')) {
                console.log('vital_BP1');
            } else {
                console.log('ERROR : vital_BP1');
            }

            // * DBP: input#vital_BP2 => value
            if (dom.querySelector('#vital_BP2')) {
                console.log('vital_BP2');
            } else {
                console.log('ERROR : vital_BP2');
            }

            // * height: input#height => value
            if (dom.querySelector('#height')) {
                console.log('height');
            } else {
                console.log('ERROR : height');
            }

            // * weight: input#weight => value
            if (dom.querySelector('#weight')) {
                console.log('weight');
            } else {
                console.log('ERROR : weight');
            }

            // * BMI: input#BMI => value
            if (dom.querySelector('#BMI')) {
                console.log('BMI');
            } else {
                console.log('ERROR : BMI');
            }

            // * spo2: input#spo2 => value
            if (dom.querySelector('#spo2')) {
                console.log('spo2');
            } else {
                console.log('ERROR : spo2');
            }

            // * breathing: input[name=Room_type]=> value
            if (dom.querySelector('input[name=Room_type]')) {
                console.log('Room_type');
            } else {
                console.log('ERROR : Room_type');
            }

            // * o2_type: input[name=via]=> value
            if (dom.querySelector('input[name=via]')) {
                console.log('via');
            } else {
                console.log('ERROR : via');
            }

            // * o2_rate: input#O2 => value
            if (dom.querySelector('#O2')) {
                console.log('O2');
            } else {
                console.log('ERROR : O2');
            }

            // * level of conscious: input[name=conscious]=> value
            if (dom.querySelector('input[name=conscious]')) {
                console.log('conscious');
            } else {
                console.log('ERROR : conscious');
            }

            // * gcs_e: input#E => value
            if (dom.querySelector('#E')) {
                console.log('E');
            } else {
                console.log('ERROR : E');
            }

            // * gcs_v: input#V => value
            if (dom.querySelector('#V')) {
                console.log('V');
            } else {
                console.log('ERROR : V');
            }

            // * gcs_m: input#M => value
            if (dom.querySelector('#M')) {
                console.log('M');
            } else {
                console.log('ERROR : M');
            }

            // * gcs: input#glassgow_detail => value
            if (dom.querySelector('#glassgow_detail')) {
                console.log('glassgow_detail');
            } else {
                console.log('ERROR : glassgow_detail');
            }

            // * mental evaluation: input[name=mental]=> value
            if (dom.querySelector('input[name=mental]')) {
                console.log('mental');
            } else {
                console.log('ERROR : mental');
            }

            // * orientation to time: input#orient_time => checked
            if (dom.querySelector('#orient_time')) {
                console.log('orient_time');
            } else {
                console.log('ERROR : orient_time');
            }

            // * orientation to place: input#orient_place => checked
            if (dom.querySelector('#orient_place')) {
                console.log('orient_place');
            } else {
                console.log('ERROR : orient_place');
            }

            // * orientation to person : input#orient_person => checked
            if (dom.querySelector('#orient_person')) {
                console.log('orient_person');
            } else {
                console.log('ERROR : orient_person');
            }

            // * General appearance: textarea#general_app => innerText
            if (dom.querySelector('#general_app')) {
                console.log('general_app');
            } else {
                console.log('ERROR : general_app');
            }

            // * exam skin: input[name=exam_skin][checked] => value
            if (dom.querySelector('input[name=exam_skin]')) {
                console.log('exam_skin');
            } else {
                console.log('ERROR : exam_skin');
            }

            // * exam skin detail: textarea#exam_skin_detail => innerText
            if (dom.querySelector('#exam_skin_detail')) {
                console.log('exam_skin_detail');
            } else {
                console.log('ERROR : exam_skin_detail');
            }

            // * exam head face: input[name=exam_face][checked] => value
            if (dom.querySelector('input[name=exam_face]')) {
                console.log('exam_face');
            } else {
                console.log('ERROR : exam_face');
            }

            // * exam head face detail: textarea#exam_face_detail => innerText
            if (dom.querySelector('#exam_face_detail')) {
                console.log('exam_face_detail');
            } else {
                console.log('ERROR : exam_face_detail');
            }

            // * exam eye ent: input[name=exam_eye][checked] => value
            if (dom.querySelector('input[name=exam_eye]')) {
                console.log('exam_eye');
            } else {
                console.log('ERROR : exam_eye');
            }

            // * exam eye ent detail: textarea#exam_eye_detail => innerText
            if (dom.querySelector('#exam_eye_detail')) {
                console.log('exam_eye_detail');
            } else {
                console.log('ERROR : exam_eye_detail');
            }

            // * exam neck: input[name=exam_neck][checked] => value
            if (dom.querySelector('input[name=exam_neck]')) {
                console.log('exam_neck');
            } else {
                console.log('ERROR : exam_neck');
            }

            // * exam neck detail: textarea#exam_neck_detail => innerText
            if (dom.querySelector('#exam_neck_detail')) {
                console.log('exam_neck_detail');
            } else {
                console.log('ERROR : exam_neck_detail');
            }

            // * exam heart: input[name=exam_heart][checked] => value
            if (dom.querySelector('input[name=exam_heart]')) {
                console.log('exam_heart');
            } else {
                console.log('ERROR : exam_heart');
            }

            // * exam heart detail: textarea#exam_heart_detail => innerText
            if (dom.querySelector('#exam_heart_detail')) {
                console.log('exam_heart_detail');
            } else {
                console.log('ERROR : exam_heart_detail');
            }

            // * exam lungs: input[name=exam_lungs][checked] => value
            if (dom.querySelector('input[name=exam_lungs]')) {
                console.log('exam_lungs');
            } else {
                console.log('ERROR : exam_lungs');
            }

            // * exam lungs detail: textarea#exam_lungs_detail => innerText
            if (dom.querySelector('#exam_lungs_detail')) {
                console.log('exam_lungs_detail');
            } else {
                console.log('ERROR : exam_lungs_detail');
            }

            // * exam abdomen: input[name=exam_abdomen][checked] => value
            if (dom.querySelector('input[name=exam_abdomen]')) {
                console.log('exam_abdomen');
            } else {
                console.log('ERROR : exam_abdomen');
            }

            // * exam abdomen detail: textarea#exam_abdomen_detail => innerText
            if (dom.querySelector('#exam_abdomen_detail')) {
                console.log('exam_abdomen_detail');
            } else {
                console.log('ERROR : exam_abdomen_detail');
            }

            // * exam Extremities: input[name=exam_extrem][checked] => value
            if (dom.querySelector('input[name=exam_extrem]')) {
                console.log('exam_extrem');
            } else {
                console.log('ERROR : exam_extrem');
            }

            // * exam Extremities detail: textarea#exam_extremities_detail => innerText
            if (dom.querySelector('#exam_extremities_detail')) {
                console.log('exam_extremities_detail');
            } else {
                console.log('ERROR : exam_extremities_detail');
            }

            // * exam Nervous system: input[name=exam_nerv][checked] => value
            if (dom.querySelector('input[name=exam_nerv]')) {
                console.log('exam_nerv');
            } else {
                console.log('ERROR : exam_nerv');
            }

            // * exam Nervous system detail: textarea#exam_nervous_detail => innerText
            if (dom.querySelector('#exam_nervous_detail')) {
                console.log('exam_nervous_detail');
            } else {
                console.log('ERROR : exam_nervous_detail');
            }

            // * exam Lymph nodes: input[name=exam_lymph][checked] => value
            if (dom.querySelector('input[name=exam_lymph]')) {
                console.log('exam_lymph');
            } else {
                console.log('ERROR : exam_lymph');
            }

            // * exam Lymph nodes detail: textarea#exam_lymph_detail => innerText
            if (dom.querySelector('#exam_lymph_detail')) {
                console.log('exam_lymph_detail');
            } else {
                console.log('ERROR : exam_lymph_detail');
            }

            // * exam breasts: input[name=exam_breasts][checked] => value
            if (dom.querySelector('input[name=exam_breasts]')) {
                console.log('exam_breasts');
            } else {
                console.log('ERROR : exam_breasts');
            }

            // * exam breasts detail: textarea#exam_breasts_detail => innerText
            if (dom.querySelector('#exam_breasts_detail')) {
                console.log('exam_breasts_detail');
            } else {
                console.log('ERROR : exam_breasts_detail');
            }

            // * exam genitalia: input[name=exam_genitalia][checked] => value
            if (dom.querySelector('input[name=exam_genitalia]')) {
                console.log('exam_genitalia');
            } else {
                console.log('ERROR : exam_genitalia');
            }

            // * exam genitalia detail: textarea#exam_genitalia_detail => innerText
            if (dom.querySelector('#exam_genitalia_detail')) {
                console.log('exam_genitalia_detail');
            } else {
                console.log('ERROR : exam_genitalia_detail');
            }

            // * exam rectal: input[name=exam_rectal][checked] => value
            if (dom.querySelector('input[name=exam_rectal]')) {
                console.log('exam_rectal');
            } else {
                console.log('ERROR : exam_rectal');
            }

            // * exam rectal detail: textarea#exam_rectal_detail => innerText
            if (dom.querySelector('#exam_rectal_detail')) {
                console.log('exam_rectal_detail');
            } else {
                console.log('ERROR : exam_rectal_detail');
            }

            // * Pertinent investigation: textarea#pertinent => innerText
            if (dom.querySelector('#pertinent')) {
                console.log('pertinent');
            } else {
                console.log('ERROR : pertinent');
            }

            // * Problem list: textarea#problem => innerText
            if (dom.querySelector('#problem')) {
                console.log('problem');
            } else {
                console.log('ERROR : problem');
            }

            // * Problem list continue: textarea#problem_cont => innerText
            if (dom.querySelector('#problem_cont')) {
                console.log('problem_cont');
            } else {
                console.log('ERROR : problem_cont');
            }

            // * discussion: textarea#discussion => innerText
            if (dom.querySelector('#discussion')) {
                console.log('discussion');
            } else {
                console.log('ERROR : discussion');
            }

            // * provisional_diagnosis: textarea#provisional_diag => innerText
            if (dom.querySelector('#provisional_diag')) {
                console.log('provisional_diag');
            } else {
                console.log('ERROR : provisional_diag');
            }

            // * Plan of investigation: textarea#plan_invest => innerText
            if (dom.querySelector('#plan_invest')) {
                console.log('plan_invest');
            } else {
                console.log('ERROR : plan_invest');
            }

            // * Plan of management: textarea#plan_manage => innerText
            if (dom.querySelector('#plan_manage')) {
                console.log('plan_manage');
            } else {
                console.log('ERROR : plan_manage');
            }

            // * plan_special_group: input#plan_special_group => checked
            if (dom.querySelector('#plan_special_group')) {
                console.log('plan_special_group');
            } else {
                console.log('ERROR : plan_special_group');
            }

            // * CPG_detail: input#CPG_detail => value
            if (dom.querySelector('#CPG_detail')) {
                console.log('CPG_detail');
            } else {
                console.log('ERROR : CPG_detail');
            }

            // * Plan of consultation: textarea#plan_consult => innerText
            if (dom.querySelector('#plan_consult')) {
                console.log('plan_consult');
            } else {
                console.log('ERROR : plan_consult');
            }

            // * can estimate los: input[name=estimated] => checked
            if (dom.querySelector('input[name=estimated]')) {
                console.log('estimated');
            } else {
                console.log('ERROR : estimated');
            }

            // * estimated los: input#length_stay => value
            if (dom.querySelector('#length_stay')) {
                console.log('length_stay');
            } else {
                console.log('ERROR : length_stay');
            }

        }

    </script>
</body>

</html>
