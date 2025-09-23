<?php



namespace App\Controller;

use App\Controller\AppController;

use App\Controller\EmailsController;

use Cake\Utility\Inflector;

use Cake\Datasource\ConnectionManager;

use Cake\Core\Configure;



//include($_SERVER['DOCUMENT_ROOT'].'/webroot/razorpay/config.php');

include($_SERVER['DOCUMENT_ROOT'] . '/webroot/razorpay/Razorpay.php');

use Razorpay\Api\Api;

use Razorpay\Api\Errors\SignatureVerificationError;



use Google\Client;

use Google\Service\FirebaseCloudMessaging;





class MembersController extends AppController
{

	public function initialize()
	{



		parent::initialize();

		$this->viewBuilder()->layout('member');

		date_default_timezone_set("Asia/Kolkata");

		$this->loadComponent('Paginator');





		//	echo $this->request->params['action'];die;

		if ($this->request->params['action'] != 'memberProfileDetails' && $this->request->params['action'] != 'memberProfile') {

			$this->loadModel('Tbl_faculty_members');

			$Admincheckid = $this->request->session()->read("Admincheck.id");

			if (empty($Admincheckid)) {

				$id = $this->request->session()->read("Tbl_faculty_members.id");

				$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

				$restd->hydrate(false);

				$datast = $restd->first();



				if (isset($datast['ref_status']) && $datast['ref_status'] != '1' && $datast['invite'] == '1') {

					$this->Flash->success('Complete your profile', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile');
					die;

				} else if (isset($datast['ref_status']) && $datast['ref_status'] != '1' && $datast['referral'] == '1') {

					$this->Flash->success('Complete your profile', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile');
					die;

				}

			}

		}

	}

	/*

							public function login()

							{ 

							 $this->loadModel('Tbl_faculty_members');

							 if($this->request->is('post'))

							   {  

								  $temuser=$this->request->data['mobile'];

								  $tempassword=base64_encode($this->request->data['password']);

										$checkdata=array(

														  "mobile"=>$temuser,

														  "acc_password"=>$tempassword,

														  'status'=>1

														 );

									$teamres =$this->Tbl_faculty_members->find("all",array("conditions"=>$checkdata,'fields'=>array('id','name','profileType','college_id')));	

									$teamres->hydrate(false);

									$teamData =  $teamres->first();



									if(!empty($teamData))

									{   

										$Admin = array();

										$this->request->session()->write("Tbl_faculty_members.id", $teamData['id']);

										$this->request->session()->write("Tbl_faculty_members.name", $teamData['name']);

										$this->request->session()->write("Tbl_faculty_members.profileType", $teamData['profileType']);

										$this->request->session()->write("Tbl_faculty_members.collegeid", $teamData['college_id']);



										return $this->redirect(webURL.'member-dashboard');die;

									}

									else

									{



										  $checkdata1=array(

														  "member_id"=>$temuser,

														  "acc_password"=>$tempassword,

														  "status"=>1,

														 );

										$teamres1 =$this->Tbl_faculty_members->find("all",array("conditions"=>$checkdata1,'fields'=>array('id','name','profileType','college_id')));	

										$teamres1->hydrate(false);

										$teamData1 =  $teamres1->first();

										if(!empty($teamData1))

									   {  

										$Admin = array();

										$this->request->session()->write("Tbl_faculty_members.id", $teamData1['id']);

										$this->request->session()->write("Tbl_faculty_members.name", $teamData1['name']);

										$this->request->session()->write("Tbl_faculty_members.profileType", $teamData1['profileType']);

										$this->request->session()->write("Tbl_faculty_members.collegeid", $teamData1['college_id']);

									   }

									  else

									   {

										$this->Flash->set('Incorrect username or password',array('key'=>'logerror'));	 

										return $this->redirect(webURL.'member-login');die;	

									   }

									}

							   }

							}

							public function companyLogin()

							{ 

							 $this->loadModel('Tbl_company_accs');

							 if($this->request->is('post'))

							   {  

								  $temuser=$this->request->data['mobile'];

								  $tempassword=base64_encode($this->request->data['password']);

										$checkdata=array(

														  "mobile"=>$temuser,

														  "acc_password"=>$tempassword,

														  "status"=>1,

														 );

									$teamres =$this->Tbl_company_accs->find("all",array("conditions"=>$checkdata,'fields'=>array('id','name','email')));	

									$teamres->hydrate(false);

									$teamData =  $teamres->first();

									if(!empty($teamData))

									{  

										$this->request->session()->write("company_accs.id", $teamData['id']);

										$this->request->session()->write("company_accs.name", $teamData['name']);



										return $this->redirect(webURL.'company-dashboard');die;

									}

									else

									{

										$this->Flash->set('Incorrect username or password',array('key'=>'logerror'));	 

										return $this->redirect(webURL.'company-login');die;

									}

							   }

							}*/





	public function dashboard()
	{



		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$id = $this->request->session()->read("Tbl_faculty_members.id");

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)), array('order' => array('id' => 'DESC')));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)), array('order' => array('id' => 'DESC')));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$this->set('viewDetails', $datast1);



		$this->loadModel('Tbl_posts');



		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$uploaded_date = date('Y-m-d h:i:s a');

		$condition = array();

		if (empty($cmpId)) {



			$usersQuery1 = $this->Tbl_member_details->find('all', array('conditions' => array('acc_id' => $stdId), 'fields' => array('course', 'acc_id')));

			$usersQuery1->hydrate(false);

			$data1 = $usersQuery1->first();



			if ($profileType != 4) {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated <=' => $uploaded_date);

			} else {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated <=' => $uploaded_date);

			}

		} else {

			$condition = array('status' => 1, 'company_id' => $cmpId, 'updated <=' => $uploaded_date);

		}

		if (isset($_GET['cat'])) {

			$this->loadModel('Wall_categorys');

			$cat = array();

			$respage = $this->Wall_categorys->find("all", array('conditions' => array('parent' => $_GET['cat'], 'status' => 1)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {
					$cat[] = $cmspages['id'];
				}

			}

			$cat[] = $_GET['cat'];

			$cat_search = array('category IN' => $cat);

			array_push($condition, $cat_search);

		}

		if (isset($_GET['type'])) {

			$type_search = array('post_type' => $_GET['type']);

			array_push($condition, $type_search);

		}

		$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

		$serser = $this->paginate('Tbl_posts');

		$this->set('blog', $serser);

	}

	public function _student_details($cors_ids = 0)
	{
		$conn = ConnectionManager::get('default');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_projects');

		$condition = array();

		$std_ids = array();



		if (!empty($cors_ids)) {

			$crs_search = array('OR' => array('Tbl_member_details.course IN' => $cors_ids));

			array_push($condition, $crs_search);

		}

		if (!empty($_GET['sc10'])) {

			$crs_search = array('Tbl_member_details.10_aggeregate >=' => $_GET['sc10']);

			array_push($condition, $crs_search);

		}

		if (!empty($_GET['sc12'])) {

			$crs_search = array('Tbl_member_details.12_aggeregate >=' => $_GET['sc12']);

			array_push($condition, $crs_search);

		}



		if (!empty($_GET['topic'])) {

			$topic_search = array('Tbl_member_details.topics LIKE' => '%' . $_GET['topic'] . '%', );

			array_push($condition, $topic_search);

		}

		if (!empty($_GET['technical'])) {

			$tech_search = array('Tbl_member_details.tech_skills LIKE' => '%' . $_GET['technical'] . '%', );

			array_push($condition, $tech_search);

		}

		if (!empty($_GET['soft'])) {

			$soft_search = array('Tbl_member_details.soft_skills LIKE' => '%' . $_GET['soft'] . '%', );

			array_push($condition, $soft_search);

		}

		if (!empty($_GET['professional'])) {

			$prof_search = array('Tbl_member_details.prof_skills LIKE' => '%' . $_GET['professional'] . '%', );

			array_push($condition, $prof_search);

		}

		if (!empty($_GET['hobbies'])) {

			$hobbies_search = array('Tbl_member_details.hobbies LIKE' => '%' . $_GET['hobbies'] . '%', );

			array_push($condition, $hobbies_search);

		}

		if (!empty($_GET['language'])) {

			$lan_search = array('Tbl_member_details.lang_know LIKE' => '%' . $_GET['language'] . '%', );

			array_push($condition, $lan_search);

		}



		$year_ids = array();

		if (isset($_GET['year']) && !empty($_GET['year'])) {



			if ($_GET['year'] != 'Alumni') {

				$stmt_year = $conn->execute('select tbl_member_details.acc_id,tbl_member_details.course,tbl_courses.duration from tbl_member_details LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_member_details.year =tbl_courses.duration');

				$results_year = $stmt_year->fetchAll('assoc');



				if (!empty($results_year)) {

					foreach ($results_year as $results_years) {



						$year_ids[] = $results_years['acc_id'];

					}

				} else {

					$year_ids[] = '000';

				}

				$year_ids_search = array('Tbl_member_details.acc_id IN' => $year_ids);

				array_push($condition, $year_ids_search);



			} else {



				$year_ids_search = array('Tbl_member_details.is_alumini' => 'Alumni');

				array_push($condition, $year_ids_search);

			}



		}



		$query = $this->Tbl_member_details->find('all', array('conditions' => $condition, "fields" => array('Tbl_member_details.acc_id', 'Tbl_member_details.edu_percentage')));

		$query->hydrate(false);

		$datastquery = $query->toArray();

		$std_eduperces = array();



		if (!empty($datastquery)) {

			if (!empty($_GET['scug'])) {

				foreach ($datastquery as $datastquerys) {

					if (!empty($datastquerys['edu_percentage'])) {

						$std_edup = explode(',', $datastquerys['edu_percentage']);

						$ttil = count($std_edup);

						$ttlperc = array_sum($std_edup);

						$average = $ttlperc / $ttil;

						if ($average >= $_GET['scug']) {

							$std_ids[] = $datastquerys['acc_id'];

						}

					}

				}

			} else {

				foreach ($datastquery as $datastquerys) {

					$std_ids[] = $datastquerys['acc_id'];

				}

			}

		}

		$stdpro_ids = array();

		if (!empty($_GET['project'])) {

			$typroject = $_GET['project'];

			$querypro = $this->Tbl_projects->find('all', array('conditions' => array('Tbl_projects.technology LIKE' => '%' . $typroject . '%'), "fields" => array('Tbl_projects.sid')));

			$querypro->hydrate(false);

			$datastqpro = $querypro->toArray();

			if (!empty($datastqpro)) {

				foreach ($datastqpro as $datastqpros) {

					$stdpro_ids[] = $datastqpros['sid'];

				}

			}

		}

		$array_ids = array_merge($std_ids, $stdpro_ids);

		$array_ids = array_unique($array_ids);

		return $array_ids;

	}



	public function studentstreamList()
	{



	}

	public function saveautoMail()
	{



		$this->loadModel('Tbl_company_accs');

		$company_id = $this->request->session()->read("company_accs.id");

		$savedata['id'] = $company_id;

		$savedata['mail_status'] = $this->request->data['status'];

		$dataToSave = $this->Tbl_company_accs->newEntity($savedata);

		$this->Tbl_company_accs->save($dataToSave);

		//$this->Flash->success('Mail status successfully updated',array('key'=>'acc_alert'));

		return $this->redirect(webURL . 'company-dashboard');
		die;



	}

	public function apiPostInterested()
	{
		$this->request->allowMethod(['post']);
		$this->loadModel('Tbl_post_interestes');
		$this->loadModel('Tbl_student_job_activities');

		$postData = $this->request->getData();

		// Get user ID from session or post data
		$std_id = $this->request->getSession()->read("Tbl_faculty_members.id");
		if (empty($std_id) && !empty($postData['user_id'])) {
			$std_id = $postData['user_id'];
		}

		// Validate input
		if (empty($std_id) || empty($postData['post_id'])) {
			return $this->response->withType('application/json')->withStringBody(json_encode([
				'status' => false,
				'msz' => 'User ID or Post ID missing.'
			]));



		}

		// Prepare interest data
		$interestData = [
			'std_id' => $std_id,
			'post_id' => $postData['post_id'],
			'date' => date('Y-m-d H:i:s')
		];

		$newInterest = $this->Tbl_post_interestes->newEntity($interestData);

		if ($this->Tbl_post_interestes->save($newInterest)) {

			// Log job activity
			$activityData = [
				'student_id' => $std_id,
				'job_id' => $postData['post_id'],
				'job_activity' => 'Interested on Post',
				'session_start_date' => date('Y-m-d'),
				'session_start_time' => date('H:i:s'),
				'platform' => 'Website'
			];

			$activity = $this->Tbl_student_job_activities->newEntity($activityData);
			$this->Tbl_student_job_activities->save($activity);

			return $this->response->withType('application/json')->withStringBody(json_encode([
				'status' => true,
				'msz' => 'Post marked as interested successfully.'
			]));
		}

		return $this->response->withType('application/json')->withStringBody(json_encode([
			'status' => false,
			'msz' => 'Something went wrong while saving data.'
		]));
	}



	// public function apiPostInterested()
// {
//     $this->request->allowMethod(['post']);
//     $this->loadModel('Tbl_post_interestes');
//     $this->loadModel('Tbl_student_job_activities');

	//     $postData = $this->request->getData();

	//     // Get user_id and post_id from the request
//     $std_id = isset($postData['user_id']) ? $postData['user_id'] : null;
//     $post_id = isset($postData['post_id']) ? $postData['post_id'] : null;

	//     // Validate input
//     if (empty($std_id) || empty($post_id)) {
//         return $this->response->withType('application/json')->withStringBody(json_encode([
//             'status' => false,
//             'message' => 'User ID or Post ID is missing.'
//         ]));
//     }

	//     // Prepare interest data
//     $interestData = [
//         'std_id' => $std_id,
//         'post_id' => $post_id,
//         'date' => date('Y-m-d H:i:s')
//     ];

	//     $newInterest = $this->Tbl_post_interestes->newEntity($interestData);

	//     if ($this->Tbl_post_interestes->save($newInterest)) {
//         // Log job activity
//         $activityData = [
//             'student_id' => $std_id,
//             'job_id' => $post_id,
//             'job_activity' => 'Interested on Post',
//             'session_start_date' => date('Y-m-d'),
//             'session_start_time' => date('H:i:s'),
//             'platform' => 'Website'
//         ];

	//         $activity = $this->Tbl_student_job_activities->newEntity($activityData);
//         $this->Tbl_student_job_activities->save($activity);

	//         return $this->response->withType('application/json')->withStringBody(json_encode([
//             'status' => true,
//             'message' => 'Post marked as interested successfully.'
//         ]));
//     }

	//     return $this->response->withType('application/json')->withStringBody(json_encode([
//         'status' => false,
//         'message' => 'Something went wrong while saving data.'
//     ]));
// }


	public function save_student_profile_view($stid = 0, $page = 0)
	{

		$this->loadModel('Tbl_student_profile_view_counters');



		$restd1 = $this->Tbl_student_profile_view_counters->find("all", array('conditions' => array('sid' => $stid, 'page' => $page), 'fields' => array('id', 'total_view')));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$savedata = array();

		if (!empty($datast1)) {

			$savedata['id'] = $datast1['id'];

			$savedata['total_view'] = $datast1['total_view'] + 1;

		} else {

			$savedata['total_view'] = 1;

		}

		$savedata['page'] = $page;

		$savedata['sid'] = $stid;

		$dataToSave = $this->Tbl_student_profile_view_counters->newEntity($savedata);

		$this->Tbl_student_profile_view_counters->save($dataToSave);



	}

	public function saveshortliststudentprofile()
	{

		if ($this->request->is('ajax')) {

			$sid = $this->request->data['sid'];

			$this->loadModel('Tbl_student_shortlisted_profiles');

			$this->loadModel('Member_credit_points');

			$this->loadModel('Shortlist_credit_points');

			$company_id = $this->request->session()->read("company_accs.id");



			$restdpoint = $this->Member_credit_points->find("all", array('conditions' => array('cmp_id' => $company_id), 'fields' => array('total_point', 'per_std_point')));

			$restdpoint->hydrate(false);

			$datapoint = $restdpoint->toArray();





			if (!empty($datapoint)) {

				$ttlpoints = array();

				foreach ($datapoint as $datapoints) {

					$ttlpoints[] = $datapoints['total_point'];

					$per_std_point = $datapoints['per_std_point'];

				}

				$totalcreadit = array_sum($ttlpoints);



				$restshortdpoint = $this->Shortlist_credit_points->find("all", array('conditions' => array('cmp_id' => $company_id), 'fields' => array('score_point')));

				$restshortdpoint->hydrate(false);

				$datashortpoint = $restshortdpoint->toArray();

				$totalshortcreadit = 0;

				if (!empty($datashortpoint)) {

					$ttlshortpoints = array();

					foreach ($datashortpoint as $datashortpoints) {

						$ttlshortpoints[] = $datashortpoints['score_point'];
					}

					$totalshortcreadit = array_sum($ttlshortpoints);

				}

				if ($totalcreadit > $totalshortcreadit) {

					$restd1 = $this->Tbl_student_shortlisted_profiles->find("all", array('conditions' => array('sid' => $sid, 'cmp_id' => $company_id), 'fields' => array('id')));

					$restd1->hydrate(false);

					$datast1 = $restd1->first();

					$savedata = array();

					$profile_name = '';

					if (isset($this->request->data['profile_name'])) {

						$profile_name = $this->request->data['profile_name'];

					}

					// return $this->request->data['profile_name'];

					// $response=array('data'=>$savedata,'message'=>'Data successfully updated');

					//     echo json_encode($response);die;	

					if (!empty($datast1)) {

						if (isset($this->request->data['status'])) {

							$savedata['id'] = $datast1['id'];

							$savedata['sid'] = $sid;

							$savedata['profile_name'] = $profile_name;

							$savedata['pro_status'] = $this->request->data['status'];

							$dataToSave = $this->Tbl_student_shortlisted_profiles->newEntity($savedata);

							$this->Tbl_student_shortlisted_profiles->save($dataToSave);

							$response = array('data' => $this->request->data['status'], 'message' => 'Data successfully updated');

							echo json_encode($response);
							die;

						} else {

							$contentemp = $this->Tbl_student_shortlisted_profiles->get($datast1['id']);

							$this->Tbl_student_shortlisted_profiles->delete($contentemp);

							if (!empty($company_id)) {

								$savedata['sid'] = $sid;

								$savedata['cmp_id'] = $company_id;

								$savedata['profile_name'] = $profile_name;

								$dataToSave = $this->Tbl_student_shortlisted_profiles->newEntity($savedata);

								$this->Tbl_student_shortlisted_profiles->save($dataToSave);



								$savedatascore['cmp_id'] = $company_id;

								$savedatascore['std_id'] = $sid;

								$savedatascore['score_point'] = $per_std_point;

								$dataToSavescore = $this->Shortlist_credit_points->newEntity($savedatascore);

								$this->Shortlist_credit_points->save($dataToSavescore);

							}

							$response = array('data' => 'Shortlist', 'message' => 'Data successfully updated');

							echo json_encode($response);
							die;

						}

					} else {



						$savedata['sid'] = $sid;

						$savedata['cmp_id'] = $company_id;

						$savedata['profile_name'] = $profile_name;

						$dataToSave = $this->Tbl_student_shortlisted_profiles->newEntity($savedata);

						$this->Tbl_student_shortlisted_profiles->save($dataToSave);



						$savedatascore['cmp_id'] = $company_id;

						$savedatascore['std_id'] = $sid;

						$savedatascore['score_point'] = $per_std_point;

						$dataToSavescore = $this->Shortlist_credit_points->newEntity($savedatascore);

						$this->Shortlist_credit_points->save($dataToSavescore);



						$creaditremove = $per_std_point + $totalshortcreadit;

						$totalremaincreadit = $totalcreadit - $creaditremove;

						$response = array('data' => 'Shortlisted', 'message' => 'Data successfully updated', 'remaincreadit' => $totalremaincreadit);

						echo json_encode($response);
						die;

					}

				} else {

					$response = array('data' => 'Error', 'message' => 'You have no credit point', 'remaincreadit' => '');

					echo json_encode($response);
					die;

				}

			} else {

				$response = array('data' => 'Error', 'message' => 'You have no credit point', 'remaincreadit' => '');

				echo json_encode($response);
				die;

			}

		} else {

			return $this->redirect($_SERVER['HTTP_REFERER']);
			die;

		}

	}



	public function updatejobfeedbackstatus()
	{

		if ($this->request->is('ajax')) {

			$this->loadModel('Tbl_shortlist_freelancers');

			$student_id = $this->request->session()->read("Tbl_faculty_members.id");

			$company_id = $this->request->data['cmp_id'];

			$restdpoint = $this->Tbl_shortlist_freelancers->find("all", array('conditions' => array('company_id' => $company_id, 'student_id' => $student_id)));

			$restdpoint->hydrate(false);

			$datapoint = $restdpoint->first();



			if (!empty($datapoint)) {

				$savedata['id'] = $datapoint['id'];

				$savedata['status'] = $this->request->data['status'];

				$savedata['accepted_at'] = date('Y-m-d');

				$dataToSave = $this->Tbl_shortlist_freelancers->newEntity($savedata);

				$this->Tbl_shortlist_freelancers->save($dataToSave);

				$response = array('data' => 1, 'message' => 'Data successfully updated');

				echo json_encode($response);
				die;



			} else {

				$response = array('data' => 2, 'message' => 'You have no credit point');

				echo json_encode($response);
				die;

			}

		} else {

			return $this->redirect($_SERVER['HTTP_REFERER']);
			die;

		}

	}



	public function _get_shortlisted_ids()
	{

		$this->loadModel('Tbl_student_shortlisted_profiles');

		$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);

		$company_id = $this->request->session()->read("company_accs.id");

		$restd1 = $this->Tbl_student_shortlisted_profiles->find("all", array('conditions' => array('cmp_id' => $company_id, 'pro_status !=' => 'Position Closed'), 'fields' => array('sid')));

		$restd1->hydrate(false);

		$results_course = $restd1->toArray();

		if (!empty($results_course)) {

			$stds = array();

			foreach ($results_course as $results_courses) {

				$stds[] = $results_courses['sid'];

			}

			return $stds;

		}

	}

	public function _get_all_student_ids($condition = 0)
	{

		$this->loadModel('Tbl_faculty_members');

		$company_id = $this->request->session()->read("company_accs.id");

		$restd1 = $this->Tbl_faculty_members->find("all", array('conditions' => $condition, 'fields' => array('id')));

		$restd1->hydrate(false);

		$results_course = $restd1->toArray();

		if (!empty($results_course)) {

			$stds = array();

			foreach ($results_course as $results_courses) {

				$stds[] = $results_courses['id'];

			}

			return $stds;

		}

	}

	public function cmpdashNew()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_specializations');

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_member_details');



		$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);



		$level = '';

		$conn = ConnectionManager::get('default');

		$cors_ids = array();
		$std_ids = array();
		$results_course = array();

		if (isset($_GET['course']) && !empty($_GET['course']) && isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  AND tbl_courses.level ="' . $_GET['level'] . '" where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			//$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where main_cat="'.$_GET['course'].'"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where level="' . $_GET['level'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['course']) && !empty($_GET['course'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');

			//	print_r($results_course);die;	

		}



		/*if(isset($_GET['course']))

														{   

														 if(!empty($results_course)){

															 foreach($results_course as $results_courses){

															   $cors_ids[]=$results_courses['id'];

															 }

														   }

														   $std_ids=$this->_student_details($cors_ids);

														   if(!empty($std_ids)){

															   $type_search = array('id IN'=>$std_ids);

														   }else{

															   $type_search = array('id IN'=>array('000'));

														   }



														   array_push($condition,$type_search);

														}*/

		if (isset($_GET['course'])) {

			$std_ids = '';

			if (!empty($results_course)) {

				foreach ($results_course as $results_courses) {

					$cors_ids[] = $results_courses['id'];

				}

			}

			if (empty($cors_ids) && !empty($_GET['course'])) {

				$std_ids = "";

			} else {

				$std_ids = $this->_student_details($cors_ids);

			}

			if (!empty($std_ids)) {

				$type_search = array('id IN' => $std_ids);

			} else {

				$type_search = array('id IN' => array('000'));

			}



			array_push($condition, $type_search);

		}

		if (isset($_GET['loc']) && !empty($_GET['loc'])) {

			$tysloc = explode(',', $_GET['loc']);

			$tyloc = array();

			if (!empty($tysloc)) {

				foreach ($tysloc as $tyslocs) {

					if (!empty($tyslocs)) {

						$tyloc[] = $tyslocs;

					}

				}

			}

			if (!empty($tyloc)) {

				$loc_search = array('OR' => array('state IN' => $tyloc, 'district IN' => $tyloc, 'city IN' => $tyloc));

				array_push($condition, $loc_search);

			}

		}

		if (isset($_GET['profile']) && !empty($_GET['profile'])) {

			$acc_search = array('acc_verify' => $_GET['profile']);

			array_push($condition, $acc_search);

		}



		$all_student_ids = array();

		$shortlisted_ids = array();

		$shortlisted_ids = $this->_get_shortlisted_ids();

		$all_student_ids = $this->_get_all_student_ids($condition);

		//	print_r($all_student_ids);die;	

		if (!empty($shortlisted_ids) && !empty($all_student_ids)) {
			$conditionshort = array();

			$ttl_student_ids = array_diff($all_student_ids, $shortlisted_ids);

			if (!empty($ttl_student_ids)) {

				$type_shortisd = array('id IN' => $ttl_student_ids);

			} else {

				$type_shortisd = array('id IN' => array('000'));

			}

			array_push($conditionshort, $type_shortisd);



			$this->paginate = (array('limit' => 10, "conditions" => $conditionshort, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		} else {



			$this->paginate = (array('limit' => 10, "conditions" => $condition, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		}





		if (!empty($datares) && isset($_GET['course'])) {

			foreach ($datares as $dataress) {

				$this->save_student_profile_view($dataress['id'], 'Search');

			}

		}

	}
	// MembersController.php
// public function cmpdashNew1()
// {
//     $this->viewBuilder()->setLayout('member');

	// 	    $this->loadModel('Tbl_faculty_members');
//     $this->loadModel('Tbl_member_details');
//     $this->loadModel('Tbl_courses');
//     $this->loadModel('Student_exam_scores');

	// 	    $freelancers = $this->Tbl_faculty_members->find()
//         ->select([
//             'Tbl_faculty_members.id',
//             'Tbl_faculty_members.college_id',
//             'Tbl_faculty_members.name',
//             'Tbl_faculty_members.email',
//             'Tbl_faculty_members.mobile',
//             'Tbl_faculty_members.state',
//             'Tbl_faculty_members.city',
//             'Tbl_faculty_members.district',
//             'Tbl_faculty_members.pincode',
//             'Tbl_faculty_members.address',
//             'Tbl_faculty_members.status',
//             'Tbl_faculty_members.regdate',

	// 	            // 👇 Aliases properly set
//             'acc_id' => 'd.acc_id',
//             'gender' => 'd.gender',
//             'religion' => 'd.religion',
//             'course' => 'd.course',
//             'semester' => 'd.semester',
//             'year' => 'd.year',
//             'caste' => 'd.caste',
//             'image_1' => 'd.image_1',
//             'edu_year' => 'd.edu_year',
//             'edu_passyear' => 'd.edu_passyear',
//             'edu_percentage' => 'd.edu_percentage',
//             'board_10' => 'd.10_board',
//             'sc_location_10' => 'd.10_sc_location',
//             'passing_year_10' => 'd.10_passing_year',
//             'aggeregate_10' => 'd.10_aggeregate',
//             'board_12' => 'd.12_board',
//             'sc_location_12' => 'd.12_sc_location',
//             'passing_year_12' => 'd.12_passing_year',
//             'aggeregate_12' => 'd.12_aggeregate',
//             'pg_board' => 'd.pg_board',
//             'pg_college' => 'd.pg_college',
//             'pg_passing_year' => 'd.pg_passing_year',
//             'pg_aggeregate' => 'd.pg_aggeregate',
//             'village' => 'd.village',
//             'tech_skills' => 'd.tech_skills',
//             'prof_skills' => 'd.prof_skills',
//             'soft_skills' => 'd.soft_skills',
//             'hobbies' => 'd.hobbies',
//             'lang_know' => 'd.lang_know',
//             'experience_details' => 'd.experience_details',
//             'freelancer_detail' => 'd.freelancer_detail'
//         ])
//         ->innerJoin(['d' => 'tbl_member_details'], 'd.acc_id = Tbl_faculty_members.id')
//         ->where([
//             'd.freelancer_detail IS NOT' => null,
//             'd.freelancer_detail !=' => ''
//         ])
//         ->enableHydration(false)
//         ->distinct(['Tbl_faculty_members.id'])
//         ->all()
//         ->toArray();

	// 	    $result = [];

	// 	    foreach ($freelancers as $freelancer) {
//         $yearText = '';
//         if (!empty($freelancer['year'])) {
//             $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
//             $abbreviation = (($freelancer['year'] % 100) >= 11 && ($freelancer['year'] % 100) <= 13)
//                 ? $freelancer['year'] . 'th Year'
//                 : $freelancer['year'] . $ends[$freelancer['year'] % 10] . ' Year';
//             $yearText = $abbreviation;
//         }

	// 	        // 🎯 Get Course Name, Duration, Level
//         $courseName = '';
//         $duration = '';
//         $level = '';
//         if (!empty($freelancer['course'])) {
//             $courseData = $this->Tbl_courses->find()
//                 ->select(['name', 'duration', 'level'])
//                 ->where(['id' => $freelancer['course']])
//                 ->first();

	// 	            if ($courseData) {
//                 $courseName = $courseData->name;
//                 $duration = $courseData->duration;
//                 $level = $courseData->level;
//             }
//         }


	// 	        $profile = !empty($freelancer['image_1'])
//             ? webURL . 'img/Member/' . $freelancer['image_1']
//             : webURL . 'img/placeholder-profile.jpg';

	// 	        $result[] = [
//             'id' => $freelancer['id'],
//             'college_id' => $freelancer['college_id'],
//             'name' => $freelancer['name'],
//             'email' => $freelancer['email'],
//             'mobile' => $freelancer['mobile'],
//             'state' => $freelancer['state'],
//             'city' => $freelancer['city'],
//             'district' => $freelancer['district'],
//             'pincode' => $freelancer['pincode'],
//             'address' => $freelancer['address'],
//             'status' => $freelancer['status'],
//             'gender' => $freelancer['gender'],
//             'religion' => $freelancer['religion'],
//             'caste' => $freelancer['caste'],
//             'semester' => $freelancer['semester'],
//             'year' => $yearText,
//             'image' => $profile,
//             'course' => $courseName,
//             'duration' => $duration,
//             'level' => $level,
//             'tech_skills' => $freelancer['tech_skills'],
//             'prof_skills' => $freelancer['prof_skills'],
//             'soft_skills' => $freelancer['soft_skills'],
//             'hobbies' => $freelancer['hobbies'],
//             'lang_know' => $freelancer['lang_know'],
//             'freelancer_detail' => $freelancer['freelancer_detail'],
//             'is_freelancer' => !empty($freelancer['freelancer_detail']) ? 1 : 0,
//             'aggeregate_10' => !empty($freelancer['aggeregate_10']) ? $freelancer['aggeregate_10'] . '%' : '',
//             'aggeregate_12' => !empty($freelancer['aggeregate_12']) ? $freelancer['aggeregate_12'] . '%' : '',
//             'board_10' => $freelancer['board_10'],
//             'sc_location_10' => $freelancer['sc_location_10'],
//             'passing_year_10' => $freelancer['passing_year_10'],
//             'board_12' => $freelancer['board_12'],
//             'sc_location_12' => $freelancer['sc_location_12'],
//             'passing_year_12' => $freelancer['passing_year_12'],
//         ];
//     }



	// 	    $this->set('viewData', $result);
// }

	//!old is besty
// public function cmpdashNew1()
// {
//     $this->viewBuilder()->setLayout('member');

	//     $this->loadModel('Tbl_faculty_members');
//     $this->loadModel('Tbl_member_details');
//     $this->loadModel('Tbl_courses');

	//     $conn = ConnectionManager::get('default');

	//     try {
//         $conditions = [
//             'd.freelancer_detail IS NOT' => null,
//             'd.freelancer_detail !=' => ''
//         ];

	//         $course = isset($_GET['course']) ? trim($_GET['course']) : null;
//         $level = isset($_GET['level']) ? trim($_GET['level']) : null;
//         $location = isset($_GET['loc']) ? trim($_GET['loc']) : null;
//         $profile = isset($_GET['profile']) ? trim($_GET['profile']) : null;

	//         $cors_ids = [];

	//         // Course + Level filtering
//         if (!empty($course) && !empty($level)) {
//             $stmt = $conn->execute(
//                 'SELECT tbl_courses.id FROM tbl_specializations 
//                  INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
//                  AND tbl_courses.level = :level 
//                  WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
//                 ['level' => $level, 'course' => $course]
//             );
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         } elseif (!empty($level)) {
//             $stmt = $conn->execute(
//                 'SELECT id FROM tbl_courses WHERE level = :level',
//                 ['level' => $level]
//             );
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         } elseif (!empty($course)) {
//             $stmt = $conn->execute(
//                 'SELECT tbl_courses.id FROM tbl_specializations 
//                  INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
//                  WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
//                 ['course' => $course]
//             );
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         }

	//         if (!empty($cors_ids)) {
//             $conditions['d.course IN'] = $cors_ids;
//         }

	//         // Location filter
//         if (!empty($location)) {
//             $locArr = array_filter(array_map('trim', explode(',', $location)));
//             if (!empty($locArr)) {
//                 $conditions[] = [
//                     'OR' => [
//                         'Tbl_faculty_members.state IN' => $locArr,
//                         'Tbl_faculty_members.district IN' => $locArr,
//                         'Tbl_faculty_members.city IN' => $locArr,
//                     ]
//                 ];
//             }
//         }

	//         // Profile verification filter
//         if (!empty($profile)) {
//             $conditions['d.acc_verify'] = $profile;
//         }

	//         // Pagination setup
//         $this->paginate = [
//             'limit' => 30,
//             'fields' => [
//                 'Tbl_faculty_members.id', 'Tbl_faculty_members.college_id', 'Tbl_faculty_members.name',
//                 'Tbl_faculty_members.email', 'Tbl_faculty_members.mobile', 'Tbl_faculty_members.state',
//                 'Tbl_faculty_members.city', 'Tbl_faculty_members.district', 'Tbl_faculty_members.pincode',
//                 'Tbl_faculty_members.address', 'Tbl_faculty_members.status', 'Tbl_faculty_members.regdate',
//                 'd.acc_id', 'd.gender', 'd.religion', 'd.course', 'd.semester', 'd.year', 'd.caste', 'd.image_1',
//                 'd.edu_year', 'd.edu_passyear', 'd.edu_percentage', 'd.10_board', 'd.10_sc_location',
//                 'd.10_passing_year', 'd.10_aggeregate', 'd.12_board', 'd.12_sc_location',
//                 'd.12_passing_year', 'd.12_aggeregate', 'd.pg_board', 'd.pg_college', 'd.pg_passing_year',
//                 'd.pg_aggeregate', 'd.village', 'd.tech_skills', 'd.prof_skills', 'd.soft_skills',
//                 'd.hobbies', 'd.lang_know', 'd.experience_details', 'd.freelancer_detail'
//             ],
//             'order' => ['Tbl_faculty_members.regdate' => 'desc'],
//             'join' => [
//                 'd' => [
//                     'table' => 'tbl_member_details',
//                     'type' => 'INNER',
//                     'conditions' => ['d.acc_id = Tbl_faculty_members.id']
//                 ]
//             ],
//             'conditions' => $conditions
//         ];

	//         $freelancers = $this->paginate('Tbl_faculty_members');

	//         $result = [];

	//         foreach ($freelancers as $freelancer) {
//             $yearText = '';
//             if (!empty($freelancer['d']['year'])) {
//                 $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
//                 $year = $freelancer['d']['year'];
//                 $suffix = (($year % 100) >= 11 && ($year % 100) <= 13) ? 'th' : $ends[$year % 10];
//                 $yearText = $year . $suffix . ' Year';
//             }

	//             $courseName = $duration = $levelName = '';
//             if (!empty($freelancer['d']['course'])) {
//                 $courseData = $this->Tbl_courses->find()
//                     ->select(['name', 'duration', 'level'])
//                     ->where(['id' => $freelancer['d']['course']])
//                     ->first();

	//                 if ($courseData) {
//                     $courseName = $courseData->name;
//                     $duration = $courseData->duration;
//                     $levelName = $courseData->level;
//                 }
//             }

	//             $imagePath = !empty($freelancer['d']['image_1'])
//                 ? webURL . 'img/Member/' . $freelancer['d']['image_1']
//                 : webURL . 'img/placeholder-profile.jpg';

	//             $result[] = [
//                 'id' => $freelancer['id'],
//                 'college_id' => $freelancer['college_id'],
//                 'name' => $freelancer['name'],
//                 'email' => $freelancer['email'],
//                 'mobile' => $freelancer['mobile'],
//                 'state' => $freelancer['state'],
//                 'city' => $freelancer['city'],
//                 'district' => $freelancer['district'],
//                 'pincode' => $freelancer['pincode'],
//                 'address' => $freelancer['address'],
//                 'status' => $freelancer['status'],
//                 'gender' => $freelancer['d']['gender'],
//                 'religion' => $freelancer['d']['religion'],
//                 'caste' => $freelancer['d']['caste'],
//                 'semester' => $freelancer['d']['semester'],
//                 'year' => $yearText,
//                 'image' => $imagePath,
//                 'course' => $courseName,
//                 'duration' => $duration,
//                 'level' => $levelName,
//                 'tech_skills' => $freelancer['d']['tech_skills'],
//                 'prof_skills' => $freelancer['d']['prof_skills'],
//                 'soft_skills' => $freelancer['d']['soft_skills'],
//                 'hobbies' => $freelancer['d']['hobbies'],
//                 'lang_know' => $freelancer['d']['lang_know'],
//                 'freelancer_detail' => $freelancer['d']['freelancer_detail'],
//                 'is_freelancer' => 1
//             ];
//         }


	//         $this->set('viewData', $result);

	//     } catch (\Exception $e) {
//         $this->log('Error in cmpdashNew1(): ' . $e->getMessage(), 'error');
//         $this->Flash->error(__('કંઇક ખોટું થયું છે. ફરીથી પ્રયાસ કરો.'));
//     }
// }


	//!new id best


	// public function cmpdashNew1()
// {
//     $this->viewBuilder()->setLayout('member');

	//     $this->loadModel('Tbl_faculty_members');
//     $this->loadModel('Tbl_member_details');
//     $this->loadModel('Tbl_courses');

	//     $conn = ConnectionManager::get('default');

	//     try {
//         $conditions = [
//             'd.freelancer_detail IS NOT' => null,
//             'd.freelancer_detail !=' => ''
//         ];

	//         $course = trim($_GET['course'] ?? '');
//         $level = trim($_GET['level'] ?? '');
//         $location = trim($_GET['loc'] ?? '');
//         $profile = trim($_GET['profile'] ?? '');
//         $sc10 = trim($_GET['sc10'] ?? '');
//         $sc12 = trim($_GET['sc12'] ?? '');
//         $scug = trim($_GET['scug'] ?? '');
//         $technical = trim($_GET['technical'] ?? '');
//         $soft = trim($_GET['soft'] ?? '');
//         $professional = trim($_GET['professional'] ?? '');
//         $hobbies = trim($_GET['hobbies'] ?? '');
//         $language = trim($_GET['language'] ?? '');
//         $project = trim($_GET['project'] ?? '');
//         $topic = trim($_GET['topic'] ?? '');
//         $score = trim($_GET['score'] ?? '');
//         $scorecutoff = trim($_GET['scorecutoff'] ?? '');

	//         $cors_ids = [];

	//         // Course + Level filtering
//         if (!empty($course) && !empty($level)) {
//             $stmt = $conn->execute(
//                 'SELECT tbl_courses.id FROM tbl_specializations 
//                  INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
//                  AND tbl_courses.level = :level 
//                  WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
//                 ['level' => $level, 'course' => $course]
//             );
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         } elseif (!empty($level)) {
//             $stmt = $conn->execute('SELECT id FROM tbl_courses WHERE level = :level', ['level' => $level]);
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         } elseif (!empty($course)) {
//             $stmt = $conn->execute(
//                 'SELECT tbl_courses.id FROM tbl_specializations 
//                  INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
//                  WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
//                 ['course' => $course]
//             );
//             $cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
//         }

	//         if (!empty($cors_ids)) {
//             $conditions['d.course IN'] = $cors_ids;
//         }

	//         // Location
//         if (!empty($location)) {
//             $locArr = array_filter(array_map('trim', explode(',', $location)));
//             if (!empty($locArr)) {
//                 $conditions[] = [
//                     'OR' => [
//                         'Tbl_faculty_members.state IN' => $locArr,
//                         'Tbl_faculty_members.district IN' => $locArr,
//                         'Tbl_faculty_members.city IN' => $locArr,
//                     ]
//                 ];
//             }
//         }

	//         // Profile verification
//         if (!empty($profile) && $profile != 2) {
//             $conditions['d.acc_verify'] = $profile;
//         }

	//         // Score filters
//         if (is_numeric($sc10)) $conditions['d.10_aggeregate >='] = $sc10;
//         if (is_numeric($sc12)) $conditions['d.12_aggeregate >='] = $sc12;
//         if (is_numeric($scug)) $conditions['d.edu_percentage >='] = $scug;

	//         // Skills filtering
//         if (!empty($technical)) $conditions['d.tech_skills LIKE'] = '%' . $technical . '%';
//         if (!empty($soft)) $conditions['d.soft_skills LIKE'] = '%' . $soft . '%';
//         if (!empty($professional)) $conditions['d.prof_skills LIKE'] = '%' . $professional . '%';
//         if (!empty($hobbies)) $conditions['d.hobbies LIKE'] = '%' . $hobbies . '%';
//         if (!empty($language)) $conditions['d.lang_know LIKE'] = '%' . $language . '%';

	//         // Tags - project and topic
//         if (!empty($project)) {
//             $tags = explode(',', $project);
//             foreach ($tags as $tag) {
//                 $tag = trim($tag);
//                 if (!empty($tag)) {
//                     $conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
//                 }
//             }
//         }

	//         if (!empty($topic)) {
//             $tags = explode(',', $topic);
//             foreach ($tags as $tag) {
//                 $tag = trim($tag);
//                 if (!empty($tag)) {
//                     $conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
//                 }
//             }
//         }

	//         // Score filter (custom logic based on your structure)
//         if (!empty($score) && is_numeric($scorecutoff)) {
//             $conditions[] = [
//                 'EXISTS (
//                     SELECT 1 FROM tbl_std_scorecard AS s
//                     WHERE s.member_id = Tbl_faculty_members.id
//                     AND s.category_id = :scorecat
//                     AND s.percentile >= :cutoff
//                 )' => true
//             ];
//             $conn->getDriver()->enableAutoQuoting(); // ensure safe binding
//         }

	//         // Pagination
//         $this->paginate = [
//             'limit' => 30,
//             'fields' => [
//                 'Tbl_faculty_members.id', 'Tbl_faculty_members.college_id', 'Tbl_faculty_members.name',
//                 'Tbl_faculty_members.email', 'Tbl_faculty_members.mobile', 'Tbl_faculty_members.state',
//                 'Tbl_faculty_members.city', 'Tbl_faculty_members.district', 'Tbl_faculty_members.pincode',
//                 'Tbl_faculty_members.address', 'Tbl_faculty_members.status', 'Tbl_faculty_members.regdate',
//                 'd.acc_id', 'd.gender', 'd.religion', 'd.course', 'd.semester', 'd.year', 'd.caste', 'd.image_1',
//                 'd.edu_year', 'd.edu_passyear', 'd.edu_percentage', 'd.10_board', 'd.10_sc_location',
//                 'd.10_passing_year', 'd.10_aggeregate', 'd.12_board', 'd.12_sc_location',
//                 'd.12_passing_year', 'd.12_aggeregate', 'd.pg_board', 'd.pg_college', 'd.pg_passing_year',
//                 'd.pg_aggeregate', 'd.village', 'd.tech_skills', 'd.prof_skills', 'd.soft_skills',
//                 'd.hobbies', 'd.lang_know', 'd.experience_details', 'd.freelancer_detail'
//             ],
//             'order' => ['Tbl_faculty_members.regdate' => 'desc'],
//             'join' => [
//                 'd' => [
//                     'table' => 'tbl_member_details',
//                     'type' => 'INNER',
//                     'conditions' => ['d.acc_id = Tbl_faculty_members.id']
//                 ]
//             ],
//             'conditions' => $conditions
//         ];

	//         $freelancers = $this->paginate('Tbl_faculty_members');

	//         $result = [];

	//         foreach ($freelancers as $freelancer) {
//             $yearText = '';
//             if (!empty($freelancer['d']['year'])) {
//                 $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
//                 $year = $freelancer['d']['year'];
//                 $suffix = (($year % 100) >= 11 && ($year % 100) <= 13) ? 'th' : $ends[$year % 10];
//                 $yearText = $year . $suffix . ' Year';
//             }

	//             $courseName = $duration = $levelName = '';
//             if (!empty($freelancer['d']['course'])) {
//                 $courseData = $this->Tbl_courses->find()
//                     ->select(['name', 'duration', 'level'])
//                     ->where(['id' => $freelancer['d']['course']])
//                     ->first();

	//                 if ($courseData) {
//                     $courseName = $courseData->name;
//                     $duration = $courseData->duration;
//                     $levelName = $courseData->level;
//                 }
//             }

	//             $imagePath = !empty($freelancer['d']['image_1'])
//                 ? webURL . 'img/Member/' . $freelancer['d']['image_1']
//                 : webURL . 'img/placeholder-profile.jpg';

	//             $result[] = [
//                 'id' => $freelancer['id'],
//                 'college_id' => $freelancer['college_id'],
//                 'name' => $freelancer['name'],
//                 'email' => $freelancer['email'],
//                 'mobile' => $freelancer['mobile'],
//                 'state' => $freelancer['state'],
//                 'city' => $freelancer['city'],
//                 'district' => $freelancer['district'],
//                 'pincode' => $freelancer['pincode'],
//                 'address' => $freelancer['address'],
//                 'status' => $freelancer['status'],
//                 'gender' => $freelancer['d']['gender'],
//                 'religion' => $freelancer['d']['religion'],
//                 'caste' => $freelancer['d']['caste'],
//                 'semester' => $freelancer['d']['semester'],
//                 'year' => $yearText,
//                 'image' => $imagePath,
//                 'course' => $courseName,
//                 'duration' => $duration,
//                 'level' => $levelName,
//                 'tech_skills' => $freelancer['d']['tech_skills'],
//                 'prof_skills' => $freelancer['d']['prof_skills'],
//                 'soft_skills' => $freelancer['d']['soft_skills'],
//                 'hobbies' => $freelancer['d']['hobbies'],
//                 'lang_know' => $freelancer['d']['lang_know'],
//                 'freelancer_detail' => $freelancer['d']['freelancer_detail'],
//                 'is_freelancer' => 1
//             ];
//         }


	//         $this->set('viewData', $result);

	//     } catch (\Exception $e) {
//         $this->log('Error in cmpdashNew1(): ' . $e->getMessage(), 'error');
//         $this->Flash->error(__('This is error'));
//     }
// }



	// public function freelancersMembers()
	// {
	// 	$this->viewBuilder()->setLayout('member');

	// 	$this->loadModel('Tbl_faculty_members');
	// 	$this->loadModel('Tbl_member_details');
	// 	$this->loadModel('Tbl_courses');

	// 	$conn = ConnectionManager::get('default');

	// 	try {
	// 		$conditions = [
	// 			'd.freelancer_detail IS NOT' => null,
	// 			'd.freelancer_detail !=' => ''
	// 		];

	// 		$course = trim($_GET['course'] ?? '');
	// 		$level = trim($_GET['level'] ?? '');
	// 		$location = trim($_GET['loc'] ?? '');
	// 		$profile = trim($_GET['profile'] ?? '');
	// 		$sc10 = trim($_GET['sc10'] ?? '');
	// 		$sc12 = trim($_GET['sc12'] ?? '');
	// 		$scug = trim($_GET['scug'] ?? '');
	// 		$technical = trim($_GET['technical'] ?? '');
	// 		$soft = trim($_GET['soft'] ?? '');
	// 		$professional = trim($_GET['professional'] ?? '');
	// 		$hobbies = trim($_GET['hobbies'] ?? '');
	// 		$language = trim($_GET['language'] ?? '');
	// 		$project = trim($_GET['project'] ?? '');
	// 		$topic = trim($_GET['topic'] ?? '');
	// 		$score = trim($_GET['score'] ?? '');
	// 		$scorecutoff = trim($_GET['scorecutoff'] ?? '');

	// 		$cors_ids = [];

	// 		if (!empty($course) && !empty($level)) {
	// 			$stmt = $conn->execute(
	// 				'SELECT tbl_courses.id FROM tbl_specializations 
	//              INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
	//              AND tbl_courses.level = :level 
	//              WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
	// 				['level' => $level, 'course' => $course]
	// 			);
	// 			$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
	// 		} elseif (!empty($level)) {
	// 			$stmt = $conn->execute('SELECT id FROM tbl_courses WHERE level = :level', ['level' => $level]);
	// 			$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
	// 		} elseif (!empty($course)) {
	// 			$stmt = $conn->execute(
	// 				'SELECT tbl_courses.id FROM tbl_specializations 
	//              INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
	//              WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
	// 				['course' => $course]
	// 			);
	// 			$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
	// 		}

	// 		if (!empty($cors_ids)) {
	// 			$conditions['d.course IN'] = $cors_ids;
	// 		}

	// 		if (!empty($location)) {
	// 			$locArr = array_filter(array_map('trim', explode(',', $location)));
	// 			if (!empty($locArr)) {
	// 				$conditions[] = [
	// 					'OR' => [
	// 						'Tbl_faculty_members.state IN' => $locArr,
	// 						'Tbl_faculty_members.district IN' => $locArr,
	// 						'Tbl_faculty_members.city IN' => $locArr,
	// 					]
	// 				];
	// 			}
	// 		}

	// 		if (!empty($profile) && $profile != 2) {
	// 			$conditions['d.acc_verify'] = $profile;
	// 		}

	// 		if (is_numeric($sc10))
	// 			$conditions['d.10_aggeregate >='] = $sc10;
	// 		if (is_numeric($sc12))
	// 			$conditions['d.12_aggeregate >='] = $sc12;
	// 		if (is_numeric($scug))
	// 			$conditions['d.edu_percentage >='] = $scug;

	// 		if (!empty($technical))
	// 			$conditions['d.tech_skills LIKE'] = '%' . $technical . '%';
	// 		if (!empty($soft))
	// 			$conditions['d.soft_skills LIKE'] = '%' . $soft . '%';
	// 		if (!empty($professional))
	// 			$conditions['d.prof_skills LIKE'] = '%' . $professional . '%';
	// 		if (!empty($hobbies))
	// 			$conditions['d.hobbies LIKE'] = '%' . $hobbies . '%';
	// 		if (!empty($language))
	// 			$conditions['d.lang_know LIKE'] = '%' . $language . '%';

	// 		if (!empty($project)) {
	// 			$tags = explode(',', $project);
	// 			foreach ($tags as $tag) {
	// 				$tag = trim($tag);
	// 				if (!empty($tag)) {
	// 					$conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
	// 				}
	// 			}
	// 		}

	// 		if (!empty($topic)) {
	// 			$tags = explode(',', $topic);
	// 			foreach ($tags as $tag) {
	// 				$tag = trim($tag);
	// 				if (!empty($tag)) {
	// 					$conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
	// 				}
	// 			}
	// 		}

	// 		if (!empty($score) && is_numeric($scorecutoff)) {
	// 			$conditions[] = [
	// 				'EXISTS (
	//                 SELECT 1 FROM tbl_std_scorecard AS s
	//                 WHERE s.member_id = Tbl_faculty_members.id
	//                 AND s.category_id = :scorecat
	//                 AND s.percentile >= :cutoff
	//             )' => true
	// 			];
	// 			$conn->getDriver()->enableAutoQuoting();
	// 		}

	// 		// ✅ LIMIT UPDATED TO 6 RECORDS PER PAGE
	// 		$this->paginate = [
	// 			'limit' => 9,
	// 			'fields' => [
	// 				'Tbl_faculty_members.id',
	// 				'Tbl_faculty_members.college_id',
	// 				'Tbl_faculty_members.name',
	// 				'Tbl_faculty_members.email',
	// 				'Tbl_faculty_members.mobile',
	// 				'Tbl_faculty_members.state',
	// 				'Tbl_faculty_members.city',
	// 				'Tbl_faculty_members.district',
	// 				'Tbl_faculty_members.pincode',
	// 				'Tbl_faculty_members.address',
	// 				'Tbl_faculty_members.status',
	// 				'Tbl_faculty_members.regdate',
	// 				'd.acc_id',
	// 				'd.gender',
	// 				'd.religion',
	// 				'd.course',
	// 				'd.semester',
	// 				'd.year',
	// 				'd.caste',
	// 				'd.image_1',
	// 				'd.edu_year',
	// 				'd.edu_passyear',
	// 				'd.edu_percentage',
	// 				'd.10_board',
	// 				'd.10_sc_location',
	// 				'd.10_passing_year',
	// 				'd.10_aggeregate',
	// 				'd.12_board',
	// 				'd.12_sc_location',
	// 				'd.12_passing_year',
	// 				'd.12_aggeregate',
	// 				'd.pg_board',
	// 				'd.pg_college',
	// 				'd.pg_passing_year',
	// 				'd.pg_aggeregate',
	// 				'd.village',
	// 				'd.tech_skills',
	// 				'd.prof_skills',
	// 				'd.soft_skills',
	// 				'd.hobbies',
	// 				'd.lang_know',
	// 				'd.experience_details',
	// 				'd.freelancer_detail'
	// 			],
	// 			'order' => ['Tbl_faculty_members.regdate' => 'desc'],
	// 			'join' => [
	// 				'd' => [
	// 					'table' => 'tbl_member_details',
	// 					'type' => 'INNER',
	// 					'conditions' => ['d.acc_id = Tbl_faculty_members.id']
	// 				]
	// 			],
	// 			'conditions' => $conditions
	// 		];

	// 		$freelancers = $this->paginate('Tbl_faculty_members');

	// 		$result = [];




	// 		foreach ($freelancers as $freelancer) {
	// 			$yearText = '';
	// 			if (!empty($freelancer['d']['year'])) {
	// 				$ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
	// 				$year = $freelancer['d']['year'];
	// 				$suffix = (($year % 100) >= 11 && ($year % 100) <= 13) ? 'th' : $ends[$year % 10];
	// 				$yearText = $year . $suffix . ' Year';
	// 			}

	// 			$courseName = $duration = $levelName = '';
	// 			if (!empty($freelancer['d']['course'])) {
	// 				$courseData = $this->Tbl_courses->find()
	// 					->select(['name', 'duration', 'level'])
	// 					->where(['id' => $freelancer['d']['course']])
	// 					->first();

	// 				if ($courseData) {
	// 					$courseName = $courseData->name;
	// 					$duration = $courseData->duration;
	// 					$levelName = $courseData->level;
	// 				}
	// 			}

	// 			$imagePath = !empty($freelancer['d']['image_1'])
	// 				? webURL . 'img/Member/' . $freelancer['d']['image_1']
	// 				: webURL . 'img/placeholder-profile.jpg';

	// 			$result[] = [
	// 				'id' => $freelancer['id'],
	// 				'college_id' => $freelancer['college_id'],
	// 				'name' => $freelancer['name'],
	// 				'email' => $freelancer['email'],
	// 				'mobile' => $freelancer['mobile'],
	// 				'state' => $freelancer['state'],
	// 				'city' => $freelancer['city'],
	// 				'district' => $freelancer['district'],
	// 				'pincode' => $freelancer['pincode'],
	// 				'address' => $freelancer['address'],
	// 				'status' => $freelancer['status'],
	// 				'gender' => $freelancer['d']['gender'],
	// 				'religion' => $freelancer['d']['religion'],
	// 				'caste' => $freelancer['d']['caste'],
	// 				'semester' => $freelancer['d']['semester'],
	// 				'year' => $yearText,
	// 				'image' => $imagePath,
	// 				'course' => $courseName,
	// 				'duration' => $duration,
	// 				'level' => $levelName,
	// 				'tech_skills' => $freelancer['d']['tech_skills'],
	// 				'prof_skills' => $freelancer['d']['prof_skills'],
	// 				'soft_skills' => $freelancer['d']['soft_skills'],
	// 				'hobbies' => $freelancer['d']['hobbies'],
	// 				'lang_know' => $freelancer['d']['lang_know'],
	// 				'freelancer_detail' => $freelancer['d']['freelancer_detail'],
	// 				'is_freelancer' => 1
	// 			];
	// 		}

	// 		echo "<pre>";
	// 		print_r($result);
	// 		echo "<pre>";

	// 		exit;

	// 		$this->set('viewData', $result);

	// 	} catch (\Exception $e) {
	// 		$this->log('Error in cmpdashNew1(): ' . $e->getMessage(), 'error');
	// 		$this->Flash->error(__('This is error'));
	// 	}
	// }



	public function freelancersMembers()
	{
		$this->viewBuilder()->setLayout('member');

		$this->loadModel('Tbl_faculty_members');
		$this->loadModel('Tbl_member_details');
		$this->loadModel('Tbl_courses');
		$this->loadModel('Tbl_schools');

		$conn = ConnectionManager::get('default');

		try {
			$conditions = [
				'd.freelancer_detail IS NOT' => null,
				'd.freelancer_detail !=' => ''
			];

			$course = trim($_GET['course'] ?? '');
			$level = trim($_GET['level'] ?? '');
			$location = trim($_GET['loc'] ?? '');
			$profile = trim($_GET['profile'] ?? '');
			$sc10 = trim($_GET['sc10'] ?? '');
			$sc12 = trim($_GET['sc12'] ?? '');
			$scug = trim($_GET['scug'] ?? '');
			$technical = trim($_GET['technical'] ?? '');
			$soft = trim($_GET['soft'] ?? '');
			$professional = trim($_GET['professional'] ?? '');
			$hobbies = trim($_GET['hobbies'] ?? '');
			$language = trim($_GET['language'] ?? '');
			$project = trim($_GET['project'] ?? '');
			$topic = trim($_GET['topic'] ?? '');
			$score = trim($_GET['score'] ?? '');
			$scorecutoff = trim($_GET['scorecutoff'] ?? '');

			$cors_ids = [];

			if (!empty($course) && !empty($level)) {
				$stmt = $conn->execute(
					'SELECT tbl_courses.id FROM tbl_specializations 
                 INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
                 AND tbl_courses.level = :level 
                 WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
					['level' => $level, 'course' => $course]
				);
				$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
			} elseif (!empty($level)) {
				$stmt = $conn->execute('SELECT id FROM tbl_courses WHERE level = :level', ['level' => $level]);
				$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
			} elseif (!empty($course)) {
				$stmt = $conn->execute(
					'SELECT tbl_courses.id FROM tbl_specializations 
                 INNER JOIN tbl_courses ON tbl_courses.specialization = tbl_specializations.name 
                 WHERE tbl_specializations.main_cat = :course OR tbl_courses.stream_title = :course',
					['course' => $course]
				);
				$cors_ids = array_column($stmt->fetchAll('assoc'), 'id');
			}

			if (!empty($cors_ids)) {
				$conditions['d.course IN'] = $cors_ids;
			}

			if (!empty($location)) {
				$locArr = array_filter(array_map('trim', explode(',', $location)));
				if (!empty($locArr)) {
					$conditions[] = [
						'OR' => [
							'Tbl_faculty_members.state IN' => $locArr,
							'Tbl_faculty_members.district IN' => $locArr,
							'Tbl_faculty_members.city IN' => $locArr,
						]
					];
				}
			}

			if (!empty($profile) && $profile != 2) {
				$conditions['d.acc_verify'] = $profile;
			}

			if (is_numeric($sc10))
				$conditions['d.10_aggeregate >='] = $sc10;
			if (is_numeric($sc12))
				$conditions['d.12_aggeregate >='] = $sc12;
			if (is_numeric($scug))
				$conditions['d.edu_percentage >='] = $scug;

			if (!empty($technical))
				$conditions['d.tech_skills LIKE'] = '%' . $technical . '%';
			if (!empty($soft))
				$conditions['d.soft_skills LIKE'] = '%' . $soft . '%';
			if (!empty($professional))
				$conditions['d.prof_skills LIKE'] = '%' . $professional . '%';
			if (!empty($hobbies))
				$conditions['d.hobbies LIKE'] = '%' . $hobbies . '%';
			if (!empty($language))
				$conditions['d.lang_know LIKE'] = '%' . $language . '%';

			if (!empty($project)) {
				$tags = explode(',', $project);
				foreach ($tags as $tag) {
					$tag = trim($tag);
					if (!empty($tag)) {
						$conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
					}
				}
			}

			if (!empty($topic)) {
				$tags = explode(',', $topic);
				foreach ($tags as $tag) {
					$tag = trim($tag);
					if (!empty($tag)) {
						$conditions[] = ['d.experience_details LIKE' => '%' . $tag . '%'];
					}
				}
			}

			if (!empty($score) && is_numeric($scorecutoff)) {
				$conditions[] = [
					'EXISTS (
                    SELECT 1 FROM tbl_std_scorecard AS s
                    WHERE s.member_id = Tbl_faculty_members.id
                    AND s.category_id = :scorecat
                    AND s.percentile >= :cutoff
                )' => true
				];
				$conn->getDriver()->enableAutoQuoting();
			}

			$this->paginate = [
				'limit' => 9,
				'fields' => [
					'Tbl_faculty_members.id',
					'Tbl_faculty_members.college_id',
					'Tbl_faculty_members.name',
					'Tbl_faculty_members.email',
					'Tbl_faculty_members.mobile',
					'Tbl_faculty_members.state',
					'Tbl_faculty_members.city',
					'Tbl_faculty_members.district',
					'Tbl_faculty_members.pincode',
					'Tbl_faculty_members.address',
					'Tbl_faculty_members.status',
					'Tbl_faculty_members.regdate',
					'd.acc_id',
					'd.gender',
					'd.religion',
					'd.course',
					'd.semester',
					'd.year',
					'd.caste',
					'd.image_1',
					'd.edu_year',
					'd.edu_passyear',
					'd.edu_percentage',
					'd.10_board',
					'd.10_sc_location',
					'd.10_passing_year',
					'd.10_aggeregate',
					'd.12_board',
					'd.12_sc_location',
					'd.12_passing_year',
					'd.12_aggeregate',
					'd.pg_board',
					'd.pg_college',
					'd.pg_passing_year',
					'd.pg_aggeregate',
					'd.village',
					'd.tech_skills',
					'd.prof_skills',
					'd.soft_skills',
					'd.hobbies',
					'd.lang_know',
					'd.experience_details',
					'd.freelancer_detail'
				],
				'order' => ['Tbl_faculty_members.regdate' => 'desc'],
				'join' => [
					'd' => [
						'table' => 'tbl_member_details',
						'type' => 'INNER',
						'conditions' => ['d.acc_id = Tbl_faculty_members.id']
					]
				],
				'conditions' => $conditions
			];

			$freelancers = $this->paginate('Tbl_faculty_members');

			$result = [];

			foreach ($freelancers as $freelancer) {
				$yearText = '';
				if (!empty($freelancer['d']['year'])) {
					$ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
					$year = $freelancer['d']['year'];
					$suffix = (($year % 100) >= 11 && ($year % 100) <= 13) ? 'th' : $ends[$year % 10];
					$yearText = $year . $suffix . ' Year';
				}

				$courseName = $duration = $levelName = '';
				if (!empty($freelancer['d']['course'])) {
					$courseData = $this->Tbl_courses->find()
						->select(['name', 'duration', 'level'])
						->where(['id' => $freelancer['d']['course']])
						->first();

					if ($courseData) {
						$courseName = $courseData->name;
						$duration = $courseData->duration;
						$levelName = $courseData->level;
					}
				}
				$collegeName = '';
				$collegeData = $this->Tbl_schools->find()
					->select(['name'])
					->where(['id' => $freelancer['college_id'] ?? 0])
					->first();

				if (!empty($collegeData)) {
					$collegeName = $collegeData->name;
				}






				$imagePath = !empty($freelancer['d']['image_1'])
					? webURL . 'img/Member/' . $freelancer['d']['image_1']
					: webURL . 'img/placeholder-profile.jpg';

				$result[] = [
					'id' => $freelancer['id'],
					'college_id' => $freelancer['college_id'],
					'college_name' => $collegeName,
					'name' => $freelancer['name'],
					'email' => $freelancer['email'],
					'mobile' => $freelancer['mobile'],
					'state' => $freelancer['state'],
					'city' => $freelancer['city'],
					'district' => $freelancer['district'],
					'pincode' => $freelancer['pincode'],
					'address' => $freelancer['address'],
					'status' => $freelancer['status'],
					'gender' => $freelancer['d']['gender'],
					'religion' => $freelancer['d']['religion'],
					'caste' => $freelancer['d']['caste'],
					'semester' => $freelancer['d']['semester'],
					'year' => $yearText,
					'image' => $imagePath,
					'course' => $courseName,
					'duration' => $duration,
					'level' => $levelName,
					'tech_skills' => $freelancer['d']['tech_skills'],
					'prof_skills' => $freelancer['d']['prof_skills'],
					'soft_skills' => $freelancer['d']['soft_skills'],
					'hobbies' => $freelancer['d']['hobbies'],
					'lang_know' => $freelancer['d']['lang_know'],
					'freelancer_detail' => $freelancer['d']['freelancer_detail'],
					'is_freelancer' => 1
				];
			}


			$this->set('viewData', $result);

		} catch (\Exception $e) {
			$this->log('Error in freelancersMembers(): ' . $e->getMessage(), 'error');
			$this->Flash->error(__('This is error'));
		}
	}

	// public function cmpdashNew1()
// {
//     $this->viewBuilder()->setLayout('member');

	//     $this->loadModel('TblMemberDetails'); // your table name
//     $this->loadModel('TblFreelancerDetails'); // related table if any

	//     // Build query conditions from GET parameters
//     $query = $this->TblMemberDetails->find()
//         ->contain(['TblFreelancerDetails']) // join relation if needed
//         ->where(['is_freelancer' => 1]); // optional: filter only freelancers

	//     // Apply filters from GET
//     $request = $this->request->getQuery();

	//     if (!empty($request['course'])) {
//         $query->where(['TblMemberDetails.course' => $request['course']]);
//     }

	//     if (!empty($request['year'])) {
//         $query->where(['TblMemberDetails.year' => $request['year']]);
//     }

	//     if (!empty($request['loc'])) {
//         $locations = explode(',', $request['loc']);
//         $query->where(function ($exp, $q) use ($locations) {
//             foreach ($locations as $loc) {
//                 $q->orWhere(['TblMemberDetails.city LIKE' => '%' . trim($loc) . '%']);
//             }
//             return $q;
//         });
//     }

	//     if (!empty($request['sc10'])) {
//         $query->where(['TblMemberDetails.score_10 >=' => $request['sc10']]);
//     }

	//     if (!empty($request['sc12'])) {
//         $query->where(['TblMemberDetails.score_12 >=' => $request['sc12']]);
//     }

	//     if (!empty($request['level'])) {
//         $query->where(['TblMemberDetails.level' => $request['level']]);
//     }

	//     if (!empty($request['profile']) && $request['profile'] == '1') {
//         $query->where(['TblMemberDetails.profile_status' => 'verified']); // adjust this field name
//     }

	//     if (!empty($request['scug'])) {
//         $query->where(['TblMemberDetails.ug_score >=' => $request['scug']]);
//     }

	//     if (!empty($request['project'])) {
//         $projectTags = explode(',', $request['project']);
//         foreach ($projectTags as $tag) {
//             $query->where(function ($exp, $q) use ($tag) {
//                 return $q->like('TblMemberDetails.projects', '%' . trim($tag) . '%');
//             });
//         }
//     }

	//     if (!empty($request['topic'])) {
//         $topicTags = explode(',', $request['topic']);
//         foreach ($topicTags as $tag) {
//             $query->where(function ($exp, $q) use ($tag) {
//                 return $q->like('TblMemberDetails.topics', '%' . trim($tag) . '%');
//             });
//         }
//     }

	//     if (!empty($request['score'])) {
//         $query->where(['TblMemberDetails.cat_score_id' => $request['score']]);
//     }

	//     if (!empty($request['scorecutoff'])) {
//         $query->where(['TblMemberDetails.percentile >=' => $request['scorecutoff']]);
//     }

	//     if (!empty($request['technical'])) {
//         $query->where(['TblMemberDetails.tech_skills LIKE' => '%' . $request['technical'] . '%']);
//     }

	//     if (!empty($request['soft'])) {
//         $query->where(['TblMemberDetails.soft_skills LIKE' => '%' . $request['soft'] . '%']);
//     }

	//     if (!empty($request['professional'])) {
//         $query->where(['TblMemberDetails.prof_skills LIKE' => '%' . $request['professional'] . '%']);
//     }

	//     if (!empty($request['hobbies'])) {
//         $query->where(['TblMemberDetails.hobbies LIKE' => '%' . $request['hobbies'] . '%']);
//     }

	//     if (!empty($request['language'])) {
//         $query->where(['TblMemberDetails.language LIKE' => '%' . $request['language'] . '%']);
//     }

	//     // Debugging - check if any data found
//     $viewData = $this->paginate($query); // with pagination
//     // OR: $viewData = $query->all(); // if not paginating

	//     // Debugging output
//     print_r($viewData->toArray());
//     exit; // Uncomment to halt execution and see data

	//     $this->set(compact('viewData'));
// }


	public function cmpDashboard()
	{





		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_specializations');

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_company_accs');

		$college_id = '';



		$company_id = $this->request->session()->read("company_accs.id");



		$checkdatamail = array('id' => $company_id);

		$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $checkdatamail, 'fields' => array('mail_status')));
		$cmpcorschkmail->hydrate(false);

		$cmpteadetail = $cmpcorschkmail->first();

		$this->set('cmpdetail', $cmpteadetail);

		if (!empty($_GET['ins'])) {

			$college_id = base64_decode($_GET['ins']);

			$condition = array('college_id' => $college_id, 'profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);

		} else {

			$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);

		}

		$level = '';

		$conn = ConnectionManager::get('default');

		$cors_ids = array();
		$std_ids = array();
		$results_course = array();


		if (isset($_GET['course']) && !empty($_GET['course']) && isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  AND tbl_courses.level ="' . $_GET['level'] . '" where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			//$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where main_cat="'.$_GET['course'].'"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where level="' . $_GET['level'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['course']) && !empty($_GET['course'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');

		}

		if (isset($_GET['course'])) {

			$std_ids = '';

			if (!empty($results_course)) {

				foreach ($results_course as $results_courses) {

					$cors_ids[] = $results_courses['id'];

				}

			}

			if (empty($cors_ids) && !empty($_GET['course'])) {

				$std_ids = "";

			} else {

				$std_ids = $this->_student_details($cors_ids);

			}

			if (!empty($std_ids)) {

				$type_search = array('id IN' => $std_ids);

			} else {

				$type_search = array('id IN' => array('000'));

			}



			array_push($condition, $type_search);

		}

		if (isset($_GET['loc']) && !empty($_GET['loc'])) {

			$tysloc = explode(',', $_GET['loc']);

			$tyloc = array();

			if (!empty($tysloc)) {

				foreach ($tysloc as $tyslocs) {

					if (!empty($tyslocs)) {

						$tyloc[] = $tyslocs;

					}

				}

			}

			if (!empty($tyloc)) {

				$loc_search = array('OR' => array('state IN' => $tyloc, 'district IN' => $tyloc, 'city IN' => $tyloc));

				array_push($condition, $loc_search);

			}

		}

		if (isset($_GET['profile']) && !empty($_GET['profile'])) {

			$acc_search = array('acc_verify' => $_GET['profile']);

			array_push($condition, $acc_search);

		}



		$all_student_ids = array();

		$shortlisted_ids = array();

		$shortlisted_ids = $this->_get_shortlisted_ids();

		$all_student_ids = $this->_get_all_student_ids($condition);

		//	print_r($all_student_ids);die;	

		if (!empty($shortlisted_ids) && !empty($all_student_ids)) {
			$conditionshort = array();

			$ttl_student_ids = array_diff($all_student_ids, $shortlisted_ids);

			if (!empty($ttl_student_ids)) {

				$type_shortisd = array('id IN' => $ttl_student_ids);

			} else {

				$type_shortisd = array('id IN' => array('000'));

			}

			array_push($conditionshort, $type_shortisd);



			$this->paginate = (array('limit' => 30, "conditions" => $conditionshort, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		} else {



			$this->paginate = (array('limit' => 30, "conditions" => $condition, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		}





		if (!empty($datares) && isset($_GET['course'])) {

			foreach ($datares as $dataress) {

				$this->save_student_profile_view($dataress['id'], 'Search');

			}

		}

	}



	public function cmpnewDashboard()
	{



		//$this->viewBuilder()->layout('');



		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_specializations');

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_company_accs');



		$company_id = $this->request->session()->read("company_accs.id");



		$checkdatamail = array('id' => $company_id);

		$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $checkdatamail, 'fields' => array('mail_status')));
		$cmpcorschkmail->hydrate(false);

		$cmpteadetail = $cmpcorschkmail->first();

		$this->set('cmpdetail', $cmpteadetail);



		$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);



		$level = '';

		$conn = ConnectionManager::get('default');

		$cors_ids = array();
		$std_ids = array();
		$results_course = array();

		/*	if(isset($_GET['course']) && !empty($_GET['course']) && isset($_GET['level']) && !empty($_GET['level'])){

															$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name OR tbl_courses.stream_title = tbl_specializations.cat_id AND tbl_courses.level ="'.$_GET['level'].'" where tbl_specializations.main_cat="'.$_GET['course'].'"');

															//$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where main_cat="'.$_GET['course'].'"');

															$results_course = $stmt_course ->fetchAll('assoc');  

															  //print_r($_GET['level']);die;	

														 }else if(isset($_GET['level']) && !empty($_GET['level'])){

															$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where level="'.$_GET['level'].'"');

															$results_course = $stmt_course ->fetchAll('assoc');



														 }

														else if(isset($_GET['course']) && !empty($_GET['course'])){

															$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name OR tbl_courses.stream_title = tbl_specializations.cat_id  where tbl_specializations.main_cat="'.$_GET['course'].'"');

															$results_course = $stmt_course->fetchAll('assoc');

														 }

														 */

		if (isset($_GET['course']) && !empty($_GET['course']) && isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  AND tbl_courses.level ="' . $_GET['level'] . '" where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			//$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where main_cat="'.$_GET['course'].'"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where level="' . $_GET['level'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['course']) && !empty($_GET['course'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');

		}

		/*if(isset($_GET['course']))

														{   

														 if(!empty($results_course)){

															 foreach($results_course as $results_courses){

															   $cors_ids[]=$results_courses['id'];

															 }

														   }

														   $std_ids=$this->_student_details($cors_ids);

														   if(!empty($std_ids)){

															   $type_search = array('id IN'=>$std_ids);

														   }else{

															   $type_search = array('id IN'=>array('000'));

														   }



														   array_push($condition,$type_search);

														}*/

		if (isset($_GET['course'])) {

			$std_ids = '';

			if (!empty($results_course)) {

				foreach ($results_course as $results_courses) {

					$cors_ids[] = $results_courses['id'];

				}

			}

			if (empty($cors_ids) && !empty($_GET['course'])) {

				$std_ids = "";

			} else {

				$std_ids = $this->_student_details($cors_ids);

			}

			if (!empty($std_ids)) {

				$type_search = array('id IN' => $std_ids);

			} else {

				$type_search = array('id IN' => array('000'));

			}



			array_push($condition, $type_search);

		}

		if (isset($_GET['loc']) && !empty($_GET['loc'])) {

			$tysloc = explode(',', $_GET['loc']);

			$tyloc = array();

			if (!empty($tysloc)) {

				foreach ($tysloc as $tyslocs) {

					if (!empty($tyslocs)) {

						$tyloc[] = $tyslocs;

					}

				}

			}

			if (!empty($tyloc)) {

				$loc_search = array('OR' => array('state IN' => $tyloc, 'district IN' => $tyloc, 'city IN' => $tyloc));

				array_push($condition, $loc_search);

			}

		}

		if (isset($_GET['profile']) && !empty($_GET['profile'])) {

			$acc_search = array('acc_verify' => $_GET['profile']);

			array_push($condition, $acc_search);

		}



		$all_student_ids = array();

		$shortlisted_ids = array();

		$shortlisted_ids = $this->_get_shortlisted_ids();

		$all_student_ids = $this->_get_all_student_ids($condition);

		//	print_r($all_student_ids);die;	

		if (!empty($shortlisted_ids) && !empty($all_student_ids)) {
			$conditionshort = array();

			$ttl_student_ids = array_diff($all_student_ids, $shortlisted_ids);

			if (!empty($ttl_student_ids)) {

				$type_shortisd = array('id IN' => $ttl_student_ids);

			} else {

				$type_shortisd = array('id IN' => array('000'));

			}

			array_push($conditionshort, $type_shortisd);



			$this->paginate = (array('limit' => 10, "conditions" => $conditionshort, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		} else {



			$this->paginate = (array('limit' => 10, "conditions" => $condition, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

			$this->set('viewData', $datares);

		}





		if (!empty($datares) && isset($_GET['course'])) {

			foreach ($datares as $dataress) {

				$this->save_student_profile_view($dataress['id'], 'Search');

			}

		}

	}



	public function studentshortlistedProfile()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_student_shortlisted_profiles');

		$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);

		$company_id = $this->request->session()->read("company_accs.id");

		$restd1 = $this->Tbl_student_shortlisted_profiles->find("all", array('conditions' => array('cmp_id' => $company_id), 'fields' => array('sid')));

		$restd1->hydrate(false);

		$results_course = $restd1->toArray();

		if (!empty($results_course)) {

			$cresultourses = array();

			foreach ($results_course as $results_courses) {

				$cresultourses[] = $results_courses['sid'];

			}

			$std_search = array('OR' => array('id IN' => $cresultourses));

			array_push($condition, $std_search);



			$this->paginate = (array('limit' => 10, "conditions" => $condition, 'fields' => array('id', 'college_id', 'name', 'district', 'state'), 'order' => array('regdate' => 'desc')));

			$datares = $this->paginate('Tbl_faculty_members');

		} else {

			$datares = '';

		}

		$this->set('viewData', $datares);

	}



	public function _getsrudent_byCategory($cat = 0)
	{

		$main_cat = '';

		$this->loadModel('Tbl_member_details');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $membid), 'fields' => array('course')));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		if (!empty($datast1['course'])) {



			$this->loadModel('Tbl_courses');

			$restdcrs = $this->Tbl_courses->find("all", array('conditions' => array('id' => $datast1['course']), 'fields' => array('stream_title', 'degree_awarded', 'specialization')));

			$restdcrs->hydrate(false);

			$datastcrs = $restdcrs->first();



			if (!empty($datastcrs)) {

				$this->loadModel('Tbl_specializations');

				$cat = array();

				if (!empty($datastcrs['specialization'])) {

					$resspec = $this->Tbl_specializations->find("all", array('conditions' => array('cat_id' => $datastcrs['stream_title'], 'name' => $datastcrs['specialization']), 'fields' => array('main_cat')));

				} else {

					$resspec = $this->Tbl_specializations->find("all", array('conditions' => array('cat_id' => $datastcrs['stream_title'], 'awd_id' => $datastcrs['degree_awarded']), 'fields' => array('main_cat')));

				}

				$resspec->hydrate(false);

				$cmsspec = $resspec->first();

				//print_r($datast1['course']);die;

				if (!empty($cmsspec)) {

					$main_cat = $cmsspec['main_cat'];

				}

			}

		}

		return $main_cat;

	}

	public function studentgridList()
	{

		$this->loadModel('Tbl_faculty_members');

		$condition = array('profileType IN' => array('3', '4'), 'new_request' => 0, 'status' => 1);

		$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('regdate' => 'desc')));

		$cmspage = $this->paginate('Tbl_faculty_members');

		$this->set('viewData', $cmspage);

	}

	public function membercollege()
	{

		$this->loadModel('Tbl_schools');

		if ($this->request->is('post')) {

			$savedata = array();

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updtdate'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			}



			if (!empty($this->request->data['clg_logo']['name'])) {

				$clg_logo = $file = $this->request->data['clg_logo'];

				$clg_logo_name = $file = $this->request->data['clg_logo']['name'];

				$clg_logo_path = $file = $this->request->data['clg_logo']['tmp_name'];

				$save_clg_logo = time() . $clg_logo_name;

				if (file_exists('img/Member/Logo/' . $this->request->data['savelogo']) && !empty($this->request->data['savelogo'])) {

					unlink('img/Member/Logo/' . $this->request->data['savelogo']);

				}

				@move_uploaded_file($file, "img/Member/Logo/" . $save_clg_logo);

				$this->request->data['logo'] = $save_clg_logo;

			}

			unset($this->request->data['clg_logo']);

			unset($this->request->data['savelogo']);

			if (!empty($this->request->data['clg_building_image']['name'])) {

				$clg_building_image = $file = $this->request->data['clg_building_image'];

				$clg_building_image_name = $file = $this->request->data['clg_building_image']['name'];

				$clg_building_image_path = $file = $this->request->data['clg_building_image']['tmp_name'];

				$save_clg_building_image = time() . $clg_building_image_name;

				if (file_exists('img/Member/' . $this->request->data['savebuildingimage']) && !empty($this->request->data['savebuildingimage'])) {

					unlink('img/Member/' . $this->request->data['savebuildingimage']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_clg_building_image);

				$this->request->data['building_image'] = $save_clg_building_image;

			}

			unset($this->request->data['clg_building_image']);

			unset($this->request->data['savebuildingimage']);



			if (!empty($this->request->data['clg_civil_lab_image']['name'])) {

				$clg_civil_lab_image = $file = $this->request->data['clg_civil_lab_image'];

				$clg_civil_lab_image_name = $file = $this->request->data['clg_civil_lab_image']['name'];

				$clg_civil_lab_image_path = $file = $this->request->data['clg_civil_lab_image']['tmp_name'];

				$save_clg_civil_lab_image = time() . $clg_civil_lab_image_name;

				if (file_exists('img/Member/' . $this->request->data['savecivillabimage']) && !empty($this->request->data['savecivillabimage'])) {

					unlink('img/Member/' . $this->request->data['savecivillabimage']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_clg_civil_lab_image);

				$this->request->data['civil_lab_image'] = $save_clg_civil_lab_image;

			}

			unset($this->request->data['clg_civil_lab_image']);

			unset($this->request->data['savecivillabimage']);



			if (!empty($this->request->data['college_fest_image1']['name'])) {

				$college_fest_image1 = $file = $this->request->data['college_fest_image1'];

				$college_fest_image1_name = $file = $this->request->data['college_fest_image1']['name'];

				$college_fest_image1_path = $file = $this->request->data['college_fest_image1']['tmp_name'];

				$save_college_fest_image1 = time() . $college_fest_image1_name;

				if (file_exists('img/Member/' . $this->request->data['savecollegefestimage1']) && !empty($this->request->data['savecollegefestimage1'])) {

					unlink('img/Member/' . $this->request->data['savecollegefestimage1']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_college_fest_image1);

				unset($this->request->data['college_fest_image1']);

				$this->request->data['college_fest_image1'] = $save_college_fest_image1;

			} else {
				unset($this->request->data['college_fest_image1']);
			}

			unset($this->request->data['savecollegefestimage1']);



			if (!empty($this->request->data['college_fest_image2']['name'])) {

				$college_fest_image2 = $file = $this->request->data['college_fest_image2'];

				$college_fest_image2_name = $file = $this->request->data['college_fest_image2']['name'];

				$college_fest_image2_path = $file = $this->request->data['college_fest_image2']['tmp_name'];

				$save_college_fest_image2 = time() . $college_fest_image2_name;

				if (file_exists('img/Member/' . $this->request->data['savecollegefestimage2']) && !empty($this->request->data['savecollegefestimage2'])) {

					unlink('img/Member/' . $this->request->data['savecollegefestimage2']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_college_fest_image2);

				unset($this->request->data['college_fest_image2']);

				$this->request->data['college_fest_image2'] = $save_college_fest_image2;

			} else {

				unset($this->request->data['college_fest_image2']);

			}

			unset($this->request->data['savecollegefestimage2']);



			//	print_r($this->request->data);die;



			$dataToSave = $this->Tbl_schools->newEntity($this->request->data);

			if ($this->Tbl_schools->save($dataToSave)) {

				$this->Flash->success('Data successfully created', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-college');
				die;

			}

		}

	}

	public function facultymembersList()
	{

		$this->loadModel('Tbl_faculty_members');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($Admincheckid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3'), 'new_request' => 0)));

		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3'), 'college_id' => $collegeid, 'new_request' => 0)));



		}

		$respage->hydrate(false);

		$teamcheck = $respage->toArray();

		$this->set('viewData', $teamcheck);

	}



	public function jobDetail($id = 0)
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_job_view_activities');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($membid)) {

			$resactivity['student_id'] = $membid;

			$resactivity['session_start_date'] = date('Y-m-d');

			$resactivity['platform'] = 'Website';

			$resactivity['session_start_time'] = date('h:i:s a');

			$resactivity['job_id'] = $id;

			$dataactivity = $this->Tbl_job_view_activities->newEntity($resactivity);

			$this->Tbl_job_view_activities->save($dataactivity);

		}

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $id)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('blogs', $cmspage);

	}



	public function memberProfile()
	{

		$this->loadModel('Tbl_faculty_members');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $membid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {



			$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

			$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (empty($teamcheck)) {



				$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

				if ($this->Tbl_faculty_members->save($dataToSave)) {



					$this->Flash->success('Data successfully update', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'member-profile');
					die;

				}

			} else {

				$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-profile');
				die;

			}

		}

	}



	public function memberProfileDetails()
	{

		$this->loadModel('Tbl_faculty_members');

		if ($this->checkCollegeRegister('profile') == 1) {

			$membid = $_GET['id'] ?? '';

			if (!empty($membid)) {

				//Adding default college ........     

				$addschool = array("id" => $membid, "college_id" => 1346);

				$dataToSave = $this->Tbl_faculty_members->newEntity($addschool);

				$this->Tbl_faculty_members->save($dataToSave);

			}

			// return $this->redirect('update-account-detail');die;

		}

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_faculty_member_experiences');

		$this->loadModel('Student_exam_scores');

		$this->loadModel('Tbl_projects');

		$this->loadModel('Tbl_assessments');

		$this->loadModel('Tbl_competitive_exams');



		$session = $this->request->session();

		$session->delete('audio_banner_id');

		$session->delete('audio_banner_id_1');

		if (!empty($_GET['id'])) {



			$membid = $_GET['id'];



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $membid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

			$this->set('viewData', $cmspage);



			$collegeId = $cmspage['college_id'] ?? $this->request->session()->read("Tbl_faculty_members.collegeid");



		} else {



			$membid = $this->request->session()->read("Tbl_faculty_members.id");

			$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $membid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

			$this->set('viewData', $cmspage);

		}





		$respaged = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $cmspage['id'])));

		$respaged->hydrate(false);

		$cmspaged = $respaged->first();

		$this->set('viewDetails', $cmspaged);





		$this->loadModel('Tbl_student_audios');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_audio_banners');



		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_audios.id,tbl_student_audios.std_id,tbl_student_audios.video_name,tbl_student_audios.date,tbl_student_audios.status,tbl_student_audios.rec_status,tbl_faculty_members.name as std_name,tbl_schools.name as college_name,tbl_audio_banners.banner from tbl_student_audios LEFT JOIN  tbl_faculty_members ON tbl_student_audios.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id LEFT JOIN  tbl_audio_banners ON tbl_audio_banners.id = tbl_student_audios.banner_id where tbl_student_audios.std_id =' . $membid . ' AND tbl_student_audios.del_status =1 order by tbl_student_audios.id desc ');

		$cmspage1 = $stmt->fetchAll('assoc');

		$this->set('audiolist', $cmspage1);



		if ($this->request->is('post')) {

			$upidlink = '';

			$upid = $this->request->data['upid'] ?? '';

			if (!empty($upid)) {
				$upidlink = '&id=' . $upid;
			}

			$teamcheck = '';

			if (isset($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);
				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();

			}

			if (empty($teamcheck)) {

				$this->request->data['invite_status'] = 1;

				$this->request->data['ref_status'] = 1;

				if (!isset($this->request->data['acc_status'])) {
					$this->request->data['status'] = 1;
				} else {
					$this->request->data['status'] = 1;
				}



				if ($this->request->data['action'] == 'education') {

					if ($this->request->data['profileType'] == '3' || $this->request->data['profileType'] == '4') {

						$membdetail['course'] = $this->request->data['course'];

						$membdetail['roll_number'] = $this->request->data['roll_number'];

					} else {

						$membdetail['course'] = '';

					}



					if (isset($this->request->data['year'])) {

						if ($this->request->data['year'] != 'Alumni') {
							$membdetail['year'] = $this->request->data['year'];
							$membdetail['is_alumini'] = '';
						} else {
							$membdetail['year'] = $this->request->data['duration'];
							$membdetail['is_alumini'] = 'Alumni';
						}

					}

					if (isset($this->request->data['semester'])) {

						$membdetail['semester'] = $this->request->data['semester'];

					}

					if (isset($this->request->data['edu_year'])) {

						$membdetail['edu_year'] = implode(',', $this->request->data['edu_year']);

					}

					if (isset($this->request->data['edu_passyear'])) {

						$membdetail['edu_passyear'] = implode(',', $this->request->data['edu_passyear']);

					}

					if (isset($this->request->data['edu_percentage'])) {

						$membdetail['edu_percentage'] = implode(',', $this->request->data['edu_percentage']);

					}

					$membdetail['pg_board'] = $this->request->data['pg_board'];

					$membdetail['pg_college'] = $this->request->data['pg_college'];

					$membdetail['pg_college_state'] = $this->request->data['pg_college_state'];

					$membdetail['pg_college_city'] = $this->request->data['pg_college_city'];

					$membdetail['pg_college_grade'] = $this->request->data['pg_college_grade'];

					$membdetail['pg_aggeregate'] = $this->request->data['pg_aggeregate'];



					$membdetail['10_board'] = $this->request->data['10_board'];

					$membdetail['10_course_name'] = $this->request->data['10_course_name'];

					$membdetail['10_college_state'] = $this->request->data['10_college_state'];

					$membdetail['10_college_city'] = $this->request->data['10_college_city'];

					$membdetail['10_grate'] = $this->request->data['10_grate'];

					$membdetail['10_aggeregate'] = $this->request->data['10_aggeregate'];



					$membdetail['12_board'] = $this->request->data['12_board'];

					$membdetail['12_course_name'] = $this->request->data['12_course_name'];

					$membdetail['12_college_state'] = $this->request->data['12_college_state'];

					$membdetail['12_college_city'] = $this->request->data['12_college_city'];

					$membdetail['12_grate'] = $this->request->data['12_grate'];

					$membdetail['12_aggeregate'] = $this->request->data['12_aggeregate'];



					$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

					if ($this->Tbl_faculty_members->save($dataToSave)) {

						$membdetail['id'] = $this->request->data['did'];

						$membdetail['acc_id'] = $this->request->data['id'];



						$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

						$this->Tbl_member_details->save($dataToSavedetl);

						//echo '<pre>';

						// print_r($membdetail);die;

					}

					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=education' . $upidlink);
					die;



				} else if ($this->request->data['action'] == 'profile') {





					if (!empty($this->request->data['image1']['name'])) {

						$image1 = $file = $this->request->data['image1'];

						$image1_name = $file = $this->request->data['image1']['name'];

						$image1_path = $file = $this->request->data['image1']['tmp_name'];

						$save_image1 = time() . $image1_name;

						if (file_exists('img/Member/' . $this->request->data['saveimage1']) && !empty($this->request->data['saveimage1'])) {

							unlink('img/Member/' . $this->request->data['saveimage1']);

						}

						@move_uploaded_file($file, "img/Member/" . $save_image1);

						$membdetail['image_1'] = $save_image1;

					}



					unset($this->request->data['image1']);

					unset($this->request->data['saveimage1']);

					if (!empty($this->request->data['file_aadhar']['name'])) {

						$file_aadhar = $file = $this->request->data['file_aadhar'];

						$file_aadhar_name = $file = $this->request->data['file_aadhar']['name'];

						$file_aadhar_path = $file = $this->request->data['file_aadhar']['tmp_name'];

						$save_file_aadhar = time() . $file_aadhar_name;

						if (file_exists('img/Member/Aadhar/' . $this->request->data['save_file_aadhar']) && !empty($this->request->data['save_file_aadhar'])) {

							unlink('img/Member/Aadhar/' . $this->request->data['save_file_aadhar']);

						}

						@move_uploaded_file($file, "img/Member/Aadhar/" . $save_file_aadhar);

						$membdetail['file_aadhar'] = $save_file_aadhar;

					}

					unset($this->request->data['file_aadhar']);

					unset($this->request->data['save_file_aadhar']);



					$membdetail['id'] = $this->request->data['did'];

					$membdetail['acc_id'] = $this->request->data['id'];

					$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

					$this->Tbl_member_details->save($dataToSavedetl);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=profile' . $upidlink);
					die;



				} else if ($this->request->data['action'] == 'personal_info') {



					$dataprof = $this->Tbl_faculty_members->newEntity($this->request->data);

					$this->Tbl_faculty_members->save($dataprof);



					$membdetail['id'] = $this->request->data['did'];

					$membdetail['dob'] = $this->request->data['dob'];

					$membdetail['gender'] = $this->request->data['gender'];

					$membdetail['language_known'] = $this->request->data['language_known'];

					$membdetail['religion'] = $this->request->data['religion'];

					$membdetail['caste'] = $this->request->data['caste'];

					$membdetail['father_name'] = $this->request->data['father_name'];

					$membdetail['father_occupation'] = $this->request->data['father_occupation'];

					$membdetail['acc_id'] = $this->request->data['id'];



					$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

					$this->Tbl_member_details->save($dataToSavedetl);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=personal_info' . $upidlink);
					die;



				} else if ($this->request->data['action'] == 'skills') {

					$membdetail['id'] = $this->request->data['did'];

					$membdetail['tech_skills'] = $this->request->data['tech_skills'];

					$membdetail['prof_skills'] = $this->request->data['prof_skills'];

					$membdetail['soft_skills'] = $this->request->data['soft_skills'];

					$membdetail['hobbies'] = $this->request->data['hobbies'];

					$membdetail['freelancer_detail'] = $this->request->data['freelancer_detail'];



					$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

					$this->Tbl_member_details->save($dataToSavedetl);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=skills' . $upidlink);
					die;



				} else if ($this->request->data['action'] == 'com_skill') {



				} else if ($this->request->data['action'] == 'address') {

					//  print_r($this->request->data);die;

					$dataprof = $this->Tbl_faculty_members->newEntity($this->request->data);

					$this->Tbl_faculty_members->save($dataprof);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=address' . $upidlink);
					die;

				} else if ($this->request->data['action'] == 'exp') {



					//$membdetail['id']=$this->request->data['exp_id'];



					$membdetail = array();

					$compayname = $this->request->data['compay_name'];



					for ($qi1 = 0; count($compayname) > $qi1; $qi1++) {



						$membdetail['std_id'] = $this->request->data['id'];

						$membdetail['id'] = $this->request->data['exp_id'][$qi1];

						$membdetail['compay_name'] = $this->request->data['compay_name'][$qi1];

						$membdetail['location'] = $this->request->data['location'][$qi1];

						$membdetail['department'] = $this->request->data['department'][$qi1];

						$membdetail['designation'] = $this->request->data['designation'][$qi1];

						$membdetail['start_duration'] = $this->request->data['start_duration'][$qi1];

						$membdetail['end_duration'] = $this->request->data['end_duration'][$qi1];

						$membdetail['job_description'] = $this->request->data['job_description'][$qi1];

						$membdetail['date'] = date('Y-m-d');



						$dataprof = $this->Tbl_faculty_member_experiences->newEntity($membdetail);

						$this->Tbl_faculty_member_experiences->save($dataprof);



						unset($membdetail);

					}



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=exp' . $upidlink);
					die;

				} else if ($this->request->data['action'] == 'project') {

					$membdetail = array();

					$compayname = $this->request->data['pro_name'];



					for ($qi1 = 0; count($compayname) > $qi1; $qi1++) {



						$membdetail['sid'] = $this->request->data['id'];

						$membdetail['id'] = $this->request->data['pro_id'][$qi1];

						$membdetail['name'] = $this->request->data['pro_name'][$qi1];

						$membdetail['location'] = $this->request->data['pro_location'][$qi1];

						$membdetail['department'] = $this->request->data['pro_department'][$qi1];

						$membdetail['designation'] = $this->request->data['pro_designation'][$qi1];

						$membdetail['duration'] = $this->request->data['pro_start_duration'][$qi1];

						$membdetail['end_duration'] = $this->request->data['pro_end_duration'][$qi1];

						$membdetail['summary'] = $this->request->data['about_project'][$qi1];

						$membdetail['date'] = date('Y-m-d');



						$dataprof = $this->Tbl_projects->newEntity($membdetail);

						$this->Tbl_projects->save($dataprof);



						unset($membdetail);

					}



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=project' . $upidlink);
					die;

				} else if ($this->request->data['action'] == 'exam') {

					$membdetail = array();

					$exam_name = $this->request->data['exam_name'];



					for ($qi1 = 0; count($exam_name) > $qi1; $qi1++) {



						$membdetail['sid'] = $this->request->data['id'];

						$membdetail['id'] = $this->request->data['exam_id'][$qi1];

						$membdetail['exam_name'] = $this->request->data['exam_name'][$qi1];

						$membdetail['exam_appeared_date'] = $this->request->data['exam_appeared_date'][$qi1];

						$membdetail['exam_score_unit'] = $this->request->data['exam_score_unit'][$qi1];

						$membdetail['exam_score_rank'] = $this->request->data['exam_score_rank'][$qi1];

						$membdetail['date'] = date('Y-m-d');

						$dataprof = $this->Tbl_competitive_exams->newEntity($membdetail);

						$this->Tbl_competitive_exams->save($dataprof);



						unset($membdetail);

					}



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=exam' . $upidlink);
					die;

				} else if ($this->request->data['action'] == 'assessment') {

					$membdetail = array();



					$membdetail['std_id'] = $this->request->data['id'];

					$membdetail['id'] = $this->request->data['assessment_id'];

					$membdetail['current_accuracy'] = $this->request->data['current_accuracy'];

					$membdetail['current_correct_ans'] = $this->request->data['current_correct_ans'];

					$membdetail['general_accuracy'] = $this->request->data['general_accuracy'];

					$membdetail['general_correct_ans'] = $this->request->data['general_correct_ans'];

					$membdetail['quantitative_accuracy'] = $this->request->data['quantitative_accuracy'];

					$membdetail['quantitative_correct_ans'] = $this->request->data['quantitative_correct_ans'];

					$membdetail['data_accuracy'] = $this->request->data['data_accuracy'];

					$membdetail['data_correct_ans'] = $this->request->data['data_correct_ans'];

					$membdetail['logical_accuracy'] = $this->request->data['logical_accuracy'];

					$membdetail['logical_correct_ans'] = $this->request->data['logical_correct_ans'];

					$membdetail['verbal_accuracy'] = $this->request->data['verbal_accuracy'];

					$membdetail['verbal_accuracy_ans'] = $this->request->data['verbal_accuracy_ans'];

					$membdetail['date'] = date('Y-m-d');



					$dataprof = $this->Tbl_assessments->newEntity($membdetail);

					$this->Tbl_assessments->save($dataprof);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'update-member-profile?action=assessment' . $upidlink);
					die;

				}



				/* if(!empty($this->request->data['image2']['name']))

																												 { 

																													 $image2= $file = $this->request->data['image2'];

																													 $image2_name = $file = $this->request->data['image2']['name'];

																													 $image2_path = $file = $this->request->data['image2']['tmp_name'];				

																													 $save_image2 = time().$image2_name;

																													 if(file_exists('img/Member/'.$this->request->data['saveimage2']) && !empty($this->request->data['saveimage2']))

																													 {

																														 unlink('img/Member/'.$this->request->data['saveimage2']);

																													 }

																													 @move_uploaded_file($file,"img/Member/".$save_image2);

																													 $membdetail['image_2']=$save_image2;

																												 }

																												 unset($this->request->data['image2']);

																												 unset($this->request->data['saveimage2']);



																											  if(!empty($this->request->data['image3']['name']))

																												 { 

																													 $image3= $file = $this->request->data['image3'];

																													 $image3_name = $file = $this->request->data['image3']['name'];

																													 $image3_path = $file = $this->request->data['image3']['tmp_name'];				

																													 $save_image3 = time().$image3_name;

																													 if(file_exists('img/Member/'.$this->request->data['saveimage3']) && !empty($this->request->data['saveimage3']))

																													 {

																														 unlink('img/Member/'.$this->request->data['saveimage3']);

																													 }

																													 @move_uploaded_file($file,"img/Member/".$save_image3);

																													 $membdetail['image_3']=$save_image3;

																												 }

																												 unset($this->request->data['image3']);

																												 unset($this->request->data['saveimage3']);



																											  if(!empty($this->request->data['image4']['name']))

																												 { 

																													 $image4= $file = $this->request->data['image4'];

																													 $image4_name = $file = $this->request->data['image4']['name'];

																													 $image4_path = $file = $this->request->data['image4']['tmp_name'];				

																													 $save_image4 = time().$image4_name;

																													 if(file_exists('img/Member/'.$this->request->data['saveimage4']) && !empty($this->request->data['saveimage4']))

																													 {

																														 unlink('img/Member/'.$this->request->data['saveimage4']);

																													 }

																													 @move_uploaded_file($file,"img/Member/".$save_image4);

																													 $membdetail['image_4']=$save_image4;

																												 }

																												 unset($this->request->data['image4']);

																												 unset($this->request->data['saveimage4']);









																											 $dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

																											 if($this->Tbl_faculty_members->save($dataToSave))

																											 { 

																											   $membdetail['acc_id']=$this->request->data['id'];

																											   $dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

																											   $this->Tbl_member_details->save($dataToSavedetl);

																											   $exambdetail=array(

																													 "id"=>$this->request->data['exam_score_id'],

																													 "std_id"=>$this->request->data['id'],

																													 "exam_name_1"=>$this->request->data['exam_name_1'],

																													 "score_1"=>$this->request->data['score_1'],

																													 "exam_name_2"=>$this->request->data['exam_name_2'],

																													 "score_2"=>$this->request->data['score_2'],

																													 "exam_name_3"=>$this->request->data['exam_name_3'],

																													 "score_3"=>$this->request->data['score_3'],

																												 );

																											   $dataToexam = $this->Student_exam_scores->newEntity($exambdetail);

																											   $this->Student_exam_scores->save($dataToexam);



																											   $this->Flash->success('Data successfully saved',array('key'=>'acc_alert'));

																											   return $this->redirect(webURL.'update-member-profile');die;

																											 } */



			} else {



				if (!empty($upid)) {
					$upidlink = '?id=' . $upid;
				}

				$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'update-member-profile' . $upidlink);
				die;

			}

		}

	}

	public function disabledAccount()
	{

		$this->loadModel('Tbl_faculty_members');

		if ($this->request->is('post')) {

			$this->request->data['id'] = $this->request->data['stid'];

			unset($this->request->data['stid']);

			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				$this->Flash->success('Data successfully updated', array('key' => 'acc_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		}

	}

	public function remove_temp_by_mobile($mobile = 0)
	{

		$this->loadModel('Tbl_student_temp_registrations');

		$retemp = $this->Tbl_student_temp_registrations->find("all", array('conditions' => array('mobile' => $mobile), 'fields' => 'id'));

		$retemp->hydrate(false);

		$datatemp = $retemp->first();

		if (!empty($datatemp)) {

			$contentemp = $this->Tbl_student_temp_registrations->get($datatemp['id']);

			$this->Tbl_student_temp_registrations->delete($contentemp);

		}

	}

	public function remove_temp_by_email($email = 0)
	{

		$this->loadModel('Tbl_student_temp_registrations');

		$retemp = $this->Tbl_student_temp_registrations->find("all", array('conditions' => array('email' => $email), 'fields' => 'id'));

		$retemp->hydrate(false);

		$datatemp = $retemp->first();

		if (!empty($datatemp)) {

			$contentemp = $this->Tbl_student_temp_registrations->get($datatemp['id']);

			$this->Tbl_student_temp_registrations->delete($contentemp);

		}

	}

	public function addFacultyMember()
	{

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_schools');

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['cid'])) {
			$cid = $_GET['cid'];
		} else {
			$cid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);

		$this->set('clgid', $cid);



		if ($this->request->is('post')) {

			if (empty($this->request->data['email'])) {

				if (empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);

				} else {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id'], 'status' => 1);

				}

				$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'admin-college-registration');
					die;

				}

				if (empty($this->request->data['id'])) {

					$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $checkdatamail, 'fields' => array('id')));

					$cmpcorschkmail->hydrate(false);

					$cmpteamcheckmail = $cmpcorschkmail->first();



					if (!empty($cmpteamcheckmail)) {



						$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

						return $this->redirect(webURL . 'admin-college-registration');
						die;

					}

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);

				} else {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();





				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'admin-college-registration');
					die;

				}

			}

			$savedata = array();

			if (!empty($this->request->data['college_id'])) {

				$savedata['id'] = $this->request->data['college_id'];

				$savedata['updtdate'] = date('Y-m-d h:i:s a');

			} else {

				$savedata['regdate'] = date('Y-m-d h:i:s a');

			}



			$tkn = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

			$savedata['name'] = $this->request->data['college_name'];

			$savedata['state'] = $this->request->data['college_state'];

			$savedata['city'] = $this->request->data['college_city'];

			$savedata['district'] = $this->request->data['college_district'];

			$savedata['pincode'] = $this->request->data['pincode'];

			$savedata['address'] = $this->request->data['college_address'];

			$savedata['status'] = 1;

			$savedata['tkn'] = $tkn;



			$dataToSch = $this->Tbl_schools->newEntity($savedata);

			$this->Tbl_schools->save($dataToSch);



			unset($this->request->data['college_id']);

			$chectkn = array('tkn' => $tkn);

			$schcrnt = $this->Tbl_schools->find("all", array("conditions" => $chectkn, 'order' => array('id' => 'DESC'), 'fields' => array('id')));

			$schcrnt->hydrate(false);

			$crntcheck = $schcrnt->first();

			$this->request->data['profileType'] = 1;

			$this->request->data['status'] = 1;

			$this->request->data['college_id'] = $crntcheck['id'];

			$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

			$this->request->data['acc_password'] = base64_encode($cpassword);



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('id', 'member_id')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;
			}

			$college_name = $this->request->data['college_name'];

			unset($this->request->data['college_name']);

			unset($this->request->data['college_state']);

			unset($this->request->data['college_city']);

			unset($this->request->data['college_district']);

			unset($this->request->data['college_address']);



			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				if (empty($this->request->data['id'])) {

					$to = webEmail . ',' . $this->request->data['email'];

					$subject = "$college_name Account Registered Successfully On LifeSet";

					$headers = "MIME-Version: 1.0\r\n";

					$headers .= 'From: info@lifeset.co.in' . "\r\n";

					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

					$body = "<html>

					<head>

					<title>$college_name Account Registered Successfully On LifeSet</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='background:#fec303; padding-left: 35px; padding-right:35px;'  ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Details  :</h4></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >User Id  : " . $this->request->data['member_id'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >Name  : " . $this->request->data['name'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >Email  : " . $this->request->data['email'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >Password  : " . $cpassword . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' >College Name  : " . $college_name . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

					</td>

					</tr>

					</table>

					</body>

					</html>";

					mail($to, $subject, $body, $headers);

				}



				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'admin-college-list');
				die;



			}



		}

		if ($type == "delete") {

			$content = $this->Tbl_schools->get($pageid);

			if ($this->Tbl_schools->delete($content)) {

				$resmem = $this->Tbl_faculty_members->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => array('id', 'mobile', 'email')));

				$resmem->hydrate(false);

				$datamem = $resmem->toArray();

				foreach ($datamem as $datamems) {



					$this->remove_temp_by_mobile($datamems['mobile']);

					$this->remove_temp_by_email($datamems['email']);



					$contentmem = $this->Tbl_faculty_members->get($datamems['id']);

					$this->Tbl_faculty_members->delete($contentmem);



					$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $datamems['id']), 'fields' => 'id'));

					$resmemdtl->hydrate(false);

					$datamemdtl = $resmemdtl->first();

					if (!empty($datamemdtl)) {

						$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

						$this->Tbl_member_details->delete($contentmemdtl);

					}

				}



				$rescrs = $this->Tbl_courses->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => 'id'));

				$rescrs->hydrate(false);

				$datacrs = $rescrs->toArray();

				if (!empty($datacrs)) {

					foreach ($datacrs as $datacrss) {

						$contentcrs = $this->Tbl_courses->get($datacrss['id']);

						$this->Tbl_courses->delete($contentcrs);

					}

				}

				$respost = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => 'id'));

				$respost->hydrate(false);

				$datapost = $respost->toArray();

				if (!empty($datapost)) {

					foreach ($datapost as $dataposts) {

						$contentpost = $this->Tbl_posts->get($dataposts['id']);

						$this->Tbl_posts->delete($contentpost);

					}

				}

				$this->Flash->success('Data sucessfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'admin-college-list');
				die;

			}

		}

	}

	public function studentverifyAccount()
	{

		if (!empty($_GET['sid']) && !empty($_GET['verify'])) {

			$savedata = array();

			$savedata['id'] = $_GET['sid'];

			$savedata['acc_verify'] = $_GET['verify'];

			$dataToSave = $this->Tbl_faculty_members->newEntity($savedata);

			$this->Tbl_faculty_members->save($dataToSave);

			$this->Flash->success('Status sucessfully updated', array('key' => 'acc_alert'));

		} else {

			$this->Flash->success('Failed to updated status', array('key' => 'acc_alert'));

		}

		return $this->redirect(webURL . 'student-list');
		die;

	}



	public function postApply($post_id = 0)
	{

		$this->loadModel('Tbl_post_applieds');



		if (!empty($post_id)) {

			$postid = base64_decode($post_id);

			$std_id = $this->request->session()->read("Tbl_faculty_members.id");

			$this->request->data['date'] = date('Y-m-d h:i:s a');

			$this->request->data['std_id'] = $std_id;

			$this->request->data['post_id'] = $postid;

			$this->request->data['post_status'] = 1;



			$dataToSave = $this->Tbl_post_applieds->newEntity($this->request->data);

			if ($this->Tbl_post_applieds->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'post_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		} else {

			return $this->redirect(webURL);
			die;

		}

	}

	public function jobApply($post_id = 0)
	{

		if ($this->checkCollegeRegister() == 1) {

			return $this->redirect('update-account-detail');
			die;

		}

		$this->loadModel('Tbl_post_applieds');



		if (!empty($post_id)) {

			$postid = base64_decode($post_id);



			$std_id = $this->request->session()->read("Tbl_faculty_members.id");

			$this->request->data['date'] = date('Y-m-d h:i:s a');

			$this->request->data['std_id'] = $std_id;

			$this->request->data['post_id'] = $postid;

			$this->request->data['post_status'] = 1;



			$dataToSave = $this->Tbl_post_applieds->newEntity($this->request->data);

			if ($this->Tbl_post_applieds->save($dataToSave)) {



				$this->loadModel('Tbl_student_job_activities');

				$this->loadModel('Tbl_faculty_members');

				$this->loadModel('Tbl_posts');







				$resactivity['student_id'] = $std_id;

				$resactivity['session_start_date'] = date('Y-m-d');

				$resactivity['platform'] = 'Website';

				$resactivity['session_start_time'] = date('h:i:s a');

				$resactivity['job_id'] = $postid;

				$resactivity['job_activity'] = 'Applied for job';

				$dataactivity = $this->Tbl_student_job_activities->newEntity($resactivity);

				$this->Tbl_student_job_activities->save($dataactivity);





				$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $std_id)), array('order' => array('id' => 'DESC')));

				$restd->hydrate(false);

				$datast = $restd->first();



				$resjob = $this->Tbl_posts->find("all", array('conditions' => array('id' => $postid)), array('order' => array('id' => 'DESC')));

				$resjob->hydrate(false);

				$datajob = $resjob->first();



				//--------------------student apply job mail 



				$email = new EmailsController;

				$toEmails[] = $datast['email'];

				$toEmails[] = 'a9urag@gmail.com';

				$resultdata['id'] = $datast['id'];

				$resultdata['name'] = $datast['name'];

				//$resultdata['email']=$datast['email'];

				$resultdata['job_title'] = $datajob['title'];



				$email->applied_job_mail($toEmails, $resultdata);





				$this->Flash->success('Data successfully saved', array('key' => 'post_alert'));

				return $this->redirect('lifeset-wall');
				die;

			}

		} else {

			return $this->redirect(webURL);
			die;

		}

	}

	public function postBookmark($post_id = 0)
	{

		$this->loadModel('Tbl_post_bookmarkes');

		$this->loadModel('Tbl_student_job_activities');



		if (!empty($post_id)) {

			$postid = base64_decode($post_id);

			$std_id = $this->request->session()->read("Tbl_faculty_members.id");

			$this->request->data['date'] = date('Y-m-d h:i:s a');

			$this->request->data['std_id'] = $std_id;

			$this->request->data['post_id'] = $postid;



			$dataToSave = $this->Tbl_post_bookmarkes->newEntity($this->request->data);

			if ($this->Tbl_post_bookmarkes->save($dataToSave)) {





				$resactivity['student_id'] = $std_id;

				$resactivity['session_start_date'] = date('Y-m-d');

				$resactivity['platform'] = 'Website';

				$resactivity['session_start_time'] = date('h:i:s a');

				$resactivity['job_id'] = $postid;

				$resactivity['job_activity'] = 'Bookmarked this job';

				$dataactivity = $this->Tbl_student_job_activities->newEntity($resactivity);

				$this->Tbl_student_job_activities->save($dataactivity);



				$this->Flash->success('Data successfully saved', array('key' => 'post_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		} else {

			return $this->redirect(webURL);
			die;

		}

	}

	public function postInterested($post_id = 0)
	{

		$this->loadModel('Tbl_post_interestes');

		$this->loadModel('Tbl_student_job_activities');

		if (!empty($post_id)) {

			$postid = base64_decode($post_id);

			$std_id = $this->request->session()->read("Tbl_faculty_members.id");

			$this->request->data['date'] = date('Y-m-d h:i:s a');

			$this->request->data['std_id'] = $std_id;

			$this->request->data['post_id'] = $postid;



			$dataToSave = $this->Tbl_post_interestes->newEntity($this->request->data);

			if ($this->Tbl_post_interestes->save($dataToSave)) {



				$resactivity['student_id'] = $std_id;

				$resactivity['session_start_date'] = date('Y-m-d');

				$resactivity['platform'] = 'Website';

				$resactivity['session_start_time'] = date('h:i:s a');

				$resactivity['job_id'] = $postid;

				$resactivity['job_activity'] = 'Interested on Post';

				$dataactivity = $this->Tbl_student_job_activities->newEntity($resactivity);

				$this->Tbl_student_job_activities->save($dataactivity);



				$this->Flash->success('Data successfully saved', array('key' => 'post_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		} else {

			return $this->redirect(webURL);
			die;

		}

	}

	public function coursesList()
	{

		$this->loadModel('Tbl_courses');

		$membid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$respage = $this->Tbl_courses->find("all", array('conditions' => array('college_id' => $membid)));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function addeditCourse()
	{

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_faculty_members');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Tbl_courses->find("all", array('conditions' => array('college_id' => $collegeId, 'id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['id'])) {

				$this->request->data['updtdate'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['regdate'] = date('Y-m-d h:i:s a');

				$this->request->data['college_id'] = $collegeId;

			}



			if (isset($this->request->data['admission_through'])) {

				$admission_through = implode(',', $this->request->data['admission_through']);

				unset($this->request->data['admission_through']);

				$this->request->data['admission_through'] = $admission_through;

			}

			if (!empty($this->request->data['total_seats'])) {

				$allseats = implode(',', $this->request->data['total_seats']);

				unset($this->request->data['total_seats']);

				$this->request->data['total_seats'] = $allseats;

			}

			if (!empty($this->request->data['stream_title'])) {

				if (!empty($this->request->data['specialization'])) {

					$this->request->data['name'] = $this->request->data['degree_awarded'] . ' in ' . $this->request->data['specialization'];

				} else {

					$this->request->data['name'] = $this->request->data['degree_awarded'];

				}



			}



			if (!empty($collegeId)) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('stream_title' => $this->request->data['stream_title'], 'degree_awarded' => $this->request->data['degree_awarded'], 'specialization' => $this->request->data['specialization'], 'course_mode' => $this->request->data['course_mode'], 'id !=' => $this->request->data['id'], 'college_id' => $collegeId);

				} else {



					$checkdata = array('stream_title' => $this->request->data['stream_title'], 'degree_awarded' => $this->request->data['degree_awarded'], 'specialization' => $this->request->data['specialization'], 'course_mode' => $this->request->data['course_mode'], 'college_id' => $collegeId);

				}



			} else {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('stream_title' => $this->request->data['stream_title'], 'degree_awarded' => $this->request->data['degree_awarded'], 'specialization' => $this->request->data['specialization'], 'course_mode' => $this->request->data['course_mode'], 'id !=' => $this->request->data['id']);

				} else {



					$checkdata = array('stream_title' => $this->request->data['stream_title'], 'degree_awarded' => $this->request->data['degree_awarded'], 'specialization' => $this->request->data['specialization'], 'course_mode' => $this->request->data['course_mode']);

				}

			}

			$corschk = $this->Tbl_courses->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (!empty($teamcheck)) {

				$this->Flash->success('This course already exist', array('key' => 'acc_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}



			$dataToSave = $this->Tbl_courses->newEntity($this->request->data);

			if ($this->Tbl_courses->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-course-list');
				die;

			}

		}
		if ($type == "delete") {

			$content = $this->Tbl_courses->get($pageid);

			if ($this->Tbl_courses->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-course-list');
				die;

			}

		}

	}
	// public function addeditCourse()
	// {
	// 	$this->loadModel('Tbl_courses');
	// 	$this->loadModel('Tbl_faculty_members');
	// 	$this->loadModel('Tbl_specializations'); // ✅ Add this to access specialization table

	// 	$membid = $this->request->session()->read("Tbl_faculty_members.id");
	// 	$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");

	// 	$pageid = isset($_GET['id']) ? $_GET['id'] : '';
	// 	$type = isset($_GET['type']) ? $_GET['type'] : '';

	// 	$respage = $this->Tbl_courses->find("all", ['conditions' => ['college_id' => $collegeId, 'id' => $pageid]]);
	// 	$respage->hydrate(false);
	// 	$cmspage = $respage->first();
	// 	$this->set('viewData', $cmspage);

	// 	// ✅ Fetch specialization list here (alphabetical order)
	// 	$specializationList = [];
	// 	if (!empty($cmspage['stream_title']) && !empty($cmspage['degree_awarded'])) {
	// 		$specializationList = $this->Tbl_specializations->find('all', [
	// 			'conditions' => [
	// 				'stream_title' => $cmspage['stream_title'],
	// 				'degree_awarded' => $cmspage['degree_awarded']
	// 			],
	// 			'order' => ['name' => 'ASC']
	// 		])->hydrate(false)->toArray();
	// 	}
	// 	$this->set('specializationList', $specializationList); // ✅ Pass to view

	// 	if ($this->request->is('post')) {
	// 		if (!empty($this->request->data['id'])) {
	// 			$this->request->data['updtdate'] = date('Y-m-d h:i:s a');
	// 		} else {
	// 			$this->request->data['regdate'] = date('Y-m-d h:i:s a');
	// 			$this->request->data['college_id'] = $collegeId;
	// 		}

	// 		if (isset($this->request->data['admission_through'])) {
	// 			$admission_through = implode(',', $this->request->data['admission_through']);
	// 			unset($this->request->data['admission_through']);
	// 			$this->request->data['admission_through'] = $admission_through;
	// 		}

	// 		if (!empty($this->request->data['total_seats'])) {
	// 			$allseats = implode(',', $this->request->data['total_seats']);
	// 			unset($this->request->data['total_seats']);
	// 			$this->request->data['total_seats'] = $allseats;
	// 		}

	// 		if (!empty($this->request->data['stream_title'])) {
	// 			if (!empty($this->request->data['specialization'])) {
	// 				$this->request->data['name'] = $this->request->data['degree_awarded'] . ' in ' . $this->request->data['specialization'];
	// 			} else {
	// 				$this->request->data['name'] = $this->request->data['degree_awarded'];
	// 			}
	// 		}

	// 		// ✅ Duplicate course check
	// 		$checkdata = [
	// 			'stream_title' => $this->request->data['stream_title'],
	// 			'degree_awarded' => $this->request->data['degree_awarded'],
	// 			'specialization' => $this->request->data['specialization'],
	// 			'course_mode' => $this->request->data['course_mode'],
	// 			'college_id' => $collegeId
	// 		];

	// 		if (!empty($this->request->data['id'])) {
	// 			$checkdata['id !='] = $this->request->data['id'];
	// 		}

	// 		$corschk = $this->Tbl_courses->find("all", [
	// 			"conditions" => $checkdata,
	// 			'fields' => ['id']
	// 		])->hydrate(false);

	// 		$teamcheck = $corschk->first();
	// 		if (!empty($teamcheck)) {
	// 			$this->Flash->success('This course already exist', ['key' => 'acc_alert']);
	// 			return $this->redirect($_SERVER['HTTP_REFERER']);
	// 		}

	// 		$dataToSave = $this->Tbl_courses->newEntity($this->request->data);
	// 		if ($this->Tbl_courses->save($dataToSave)) {
	// 			$this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);
	// 			return $this->redirect(webURL . 'member-course-list');
	// 		}
	// 	}

	// 	if ($type == "delete") {
	// 		$content = $this->Tbl_courses->get($pageid);
	// 		if ($this->Tbl_courses->delete($content)) {
	// 			$this->Flash->success('Data successfully deleted', ['key' => 'acc_alert']);
	// 			return $this->redirect(webURL . 'member-course-list');
	// 		}
	// 	}
	// }

	public function addeditcourseRequest()
	{

		$this->loadModel('Tbl_faculty_members');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $membid), 'fields' => array('id', 'name', 'mobile', 'email')));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			$this->loadModel('Tbl_course_requests');

			$this->request->data['college_id'] = $collegeId;

			$dataToSave = $this->Tbl_course_requests->newEntity($this->request->data);

			if ($this->Tbl_course_requests->save($dataToSave)) {

				$this->Flash->success('Request successfully sent', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'add-course-request');
				die;

			}

		}

	}



	public function courserequestList()
	{

		$this->loadModel('Tbl_course_requests');

		$respage = '';

		$respage = $this->Tbl_course_requests->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('result', $cmspage);

	}

	public function memberRegistration()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_company_accs');

		if ($this->request->is('post')) {



			$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);



			$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

			$corschkmail->hydrate(false);

			$teamcheckmail = $corschkmail->first();



			$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $checkdatamail, 'fields' => array('id')));

			$cmpcorschkmail->hydrate(false);

			$cmpteamcheckmail = $cmpcorschkmail->first();



			if (!empty($this->request->data['email'])) {

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'admin-college-registration');
					die;

				} else if (!empty($cmpteamcheckmail)) {



					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'admin-college-registration');
					die;

				}

			}



			if (!empty($this->request->data['mobile'])) {

				$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();

				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'logerr'));

					return $this->redirect(webURL . 'team-registration');
					die;

				}

			}

			$this->request->data['regdate'] = 1;

			$this->request->data['datetime'] = date('Y-m-d h:i:s a');

			$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

			$this->request->data['acc_password'] = base64_encode($cpassword);



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('id', 'member_id')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;
			}



			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {



				if ($this->request->data['profileType'] == '2') {

					$headtitle = "Faculty Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '3') {

					$headtitle = "Studnt Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '1') {

					$headtitle = "Faculty Head Account Registered Successfully On LifeSet";

				}

				$to = webEmail . ',' . $this->request->data['email'];

				$subject = $headtitle;

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

					<head>

					<title>" . $headtitle . "</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Member Details  :</h4></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>User Id  : " . $this->request->data['member_id'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>State  : " . $this->request->data['state'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>District  : " . $this->request->data['district'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>City  : " . $this->request->data['city'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Designation  : " . $this->request->data['designation'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Pincode  : " . $this->request->data['pincode'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address  : " . $this->request->data['address'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

					</td>

					</tr>

					</table>

					</body>

					</html>";

				if (mail($to, $subject, $body, $headers)) {



					$this->request->session()->write("Team.dfname", $teamteam['name']);

					$this->request->session()->write("Team.dfid", $teamteam['userid']);

					$this->Flash->success('Profile successfully created', array('key' => 'acc'));

					return $this->redirect(webURL . 'team-thanks');
					die;

				}

			}



		}

	}

	public function collegeList()
	{

		$this->loadModel('Tbl_schools');

		$respage = $this->Tbl_schools->find("all", array('conditions' => array('new_request' => 0)), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();



		$this->set('viewData', $cmspage);

	}



	// 	public function instituteCount()
// {
//     $this->loadModel('Tbl_schools');
//     $this->loadModel('Tbl_courses');
//     $this->loadModel('Tbl_faculty_members');

	//     $respage = $this->Tbl_schools->find("all", [
//         'conditions' => ['new_request' => 0],
//         'order' => ['id' => 'DESC']
//     ])->hydrate(false);

	//     $cmspage = $respage->toArray();

	//     foreach ($cmspage as &$college) {

	//         // Count total courses for this college
//         $courseCount = $this->Tbl_courses->find()
//             ->where(['college_id' => $college['id']])
//             ->count();

	//         // Add clickable link for course count
//         $college['course_count'] = $courseCount ;
// '<a href="https://lifeset.co.in/member-course-list?college_id=' 
//             . $college['id'] . '" target="_blank">' . . '</a>'
//         // Count total faculty members (or students) for this college
//         $studentCount = $this->Tbl_faculty_members->find()
//             ->where(['college_id' => $college['id']])
//             ->count();

	//         $college['students_count'] = $studentCount;
//     }




	//     // Set data to view (in case you remove exit later)
//     $this->set('viewData', $cmspage);
// }

	public function instituteCount()
	{
		$this->loadModel('Tbl_schools');
		$this->loadModel('Tbl_courses');
		$this->loadModel('Tbl_faculty_members');

		$respage = $this->Tbl_schools->find("all", [
			'conditions' => ['new_request' => 0],
			'order' => ['id' => 'DESC']
		])->hydrate(false);

		$cmspage = $respage->toArray();

		foreach ($cmspage as &$college) {

			// Count total courses for this college
			$courseCount = $this->Tbl_courses->find()
				->where(['college_id' => $college['id']])
				->count();

			// Just assign the course count (No hyperlink)
			$college['course_count'] = $courseCount;

			// Count total faculty members (or students) for this college
			$studentCount = $this->Tbl_faculty_members->find()
				->where(['college_id' => $college['id']])
				->count();

			$college['students_count'] = $studentCount;
		}

		// Set data to view
		$this->set('viewData', $cmspage);
	}


	public function addcollege()
	{

		$this->loadModel('Tbl_schools');

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$respage = $this->Tbl_schools->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['clg_logo']['name'])) {

				$clg_logo = $file = $this->request->data['clg_logo'];

				$clg_logo_name = $file = $this->request->data['clg_logo']['name'];

				$clg_logo_path = $file = $this->request->data['clg_logo']['tmp_name'];

				$save_clg_logo = time() . $clg_logo_name;

				if (file_exists('img/Member/Logo/' . $this->request->data['savelogo'])) {

					unlink('img/Member/Logo/' . $this->request->data['savelogo']);

				}

				@move_uploaded_file($file, "img/Member/Logo/" . $save_clg_logo);

				$this->request->data['logo'] = $save_clg_logo;

			}

			unset($this->request->data['clg_logo']);

			unset($this->request->data['savelogo']);

			print_r($this->request->data);
			die;

			if (!empty($this->request->data['clg_building_image']['name'])) {

				$clg_building_image = $file = $this->request->data['clg_building_image'];

				$clg_building_image_name = $file = $this->request->data['clg_building_image']['name'];

				$clg_building_image_path = $file = $this->request->data['clg_building_image']['tmp_name'];

				$save_clg_building_image = time() . $clg_building_image_name;

				if (file_exists('img/Member/' . $this->request->data['savebuildingimage'])) {

					unlink('img/Member/' . $this->request->data['savebuildingimage']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_clg_building_image);

				$this->request->data['building_image'] = $save_clg_building_image;

			}

			unset($this->request->data['clg_building_image']);

			unset($this->request->data['savebuildingimage']);



			if (!empty($this->request->data['clg_civil_lab_image']['name'])) {

				$clg_civil_lab_image = $file = $this->request->data['clg_civil_lab_image'];

				$clg_civil_lab_image_name = $file = $this->request->data['clg_civil_lab_image']['name'];

				$clg_civil_lab_image_path = $file = $this->request->data['clg_civil_lab_image']['tmp_name'];

				$save_clg_civil_lab_image = time() . $clg_civil_lab_image_name;

				if (file_exists('img/Member/' . $this->request->data['savecivillabimage'])) {

					unlink('img/Member/' . $this->request->data['savecivillabimage']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_clg_civil_lab_image);

				$this->request->data['civil_lab_image'] = $save_clg_civil_lab_image;

			}

			unset($this->request->data['clg_civil_lab_image']);

			unset($this->request->data['savecivillabimage']);



			if (!empty($this->request->data['college_fest_image1']['name'])) {

				$college_fest_image1 = $file = $this->request->data['college_fest_image1'];

				$college_fest_image1_name = $file = $this->request->data['college_fest_image1']['name'];

				$college_fest_image1_path = $file = $this->request->data['college_fest_image1']['tmp_name'];

				$save_college_fest_image1 = time() . $college_fest_image1_name;

				if (file_exists('img/Member/' . $this->request->data['savecollegefestimage1'])) {

					unlink('img/Member/' . $this->request->data['savecollegefestimage1']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_college_fest_image1);

				unset($this->request->data['college_fest_image1']);

				$this->request->data['college_fest_image1'] = $save_college_fest_image1;

			} else {
				unset($this->request->data['college_fest_image1']);
			}

			unset($this->request->data['savecollegefestimage1']);



			if (!empty($this->request->data['college_fest_image2']['name'])) {

				$college_fest_image2 = $file = $this->request->data['college_fest_image2'];

				$college_fest_image2_name = $file = $this->request->data['college_fest_image2']['name'];

				$college_fest_image2_path = $file = $this->request->data['college_fest_image2']['tmp_name'];

				$save_college_fest_image2 = time() . $college_fest_image2_name;

				if (file_exists('img/Member/' . $this->request->data['savecollegefestimage2'])) {

					unlink('img/Member/' . $this->request->data['savecollegefestimage2']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_college_fest_image2);

				unset($this->request->data['college_fest_image2']);

				$this->request->data['college_fest_image2'] = $save_college_fest_image2;

			} else {

				unset($this->request->data['college_fest_image2']);

			}

			unset($this->request->data['savecollegefestimage2']);



			$dataToSave = $this->Tbl_schools->newEntity($this->request->data);

			if ($this->Tbl_schools->save($dataToSave)) {

				$this->Flash->success('Data successfully created', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'admin-college-list');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Tbl_schools->get($pageid);

			if ($this->Tbl_schools->delete($content)) {

				$resmem = $this->Tbl_faculty_members->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => array('id', 'mobile', 'email')));

				$resmem->hydrate(false);

				$datamem = $resmem->toArray();

				if (!empty($datamem)) {

					foreach ($datamem as $datamems) {



						$this->remove_temp_by_mobile($datamems['mobile']);

						$this->remove_temp_by_email($datamems['email']);



						$contentmem = $this->Tbl_faculty_members->get($datamems['id']);

						$this->Tbl_faculty_members->delete($contentmem);



						$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $datamems['id']), 'fields' => 'id'));

						$resmemdtl->hydrate(false);

						$datamemdtl = $resmemdtl->first();

						if (!empty($datamemdtl)) {

							$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

							$this->Tbl_member_details->delete($contentmemdtl);

						}

					}

				}

				$rescrs = $this->Tbl_courses->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => 'id'));

				$rescrs->hydrate(false);

				$datacrs = $rescrs->toArray();

				if (!empty($datacrs)) {

					foreach ($datacrs as $datacrss) {

						$contentcrs = $this->Tbl_courses->get($datacrss['id']);

						$this->Tbl_courses->delete($contentcrs);

					}

				}

				$respost = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $pageid), 'fields' => 'id'));

				$respost->hydrate(false);

				$datapost = $respost->toArray();

				if (!empty($datapost)) {

					foreach ($datapost as $dataposts) {

						$contentpost = $this->Tbl_posts->get($dataposts['id']);

						$this->Tbl_posts->delete($contentpost);

					}

				}

				$this->Flash->success('Data sucessfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'admin-college-list');
				die;

			}

		}

	}

	public function companylistView()
	{

		$this->loadModel('Tbl_company_accs');

		$respage = $this->Tbl_company_accs->find("all", array('conditions' => array('new_request' => 0)), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function viewStudentList()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_schools');

		if (isset($_GET['st'])) {
			$st = $_GET['st'];
		} else {
			$st = '';
		}

		if (isset($_GET['cs'])) {
			$cid = $_GET['cs'];
		} else {
			$cid = '';
		}

		if (isset($_GET['y'])) {
			$yid = $_GET['y'];
		} else {
			$yid = '';
		}




		$this->set('courseid', $cid);

		$this->set('yearid', $yid);



	}





	public function memberList()
	{

		$this->loadModel('Tbl_faculty_members');



		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3'), 'college_id' => $collegeid, 'new_request' => 0)), array('order' => array('id' => 'DESC')));



		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3'), 'new_request' => 0)), array('order' => array('id' => 'DESC')));

		}

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function studentList()
	{



		$this->loadModel('Tbl_faculty_members');



		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		$key = $_GET['search'];

		$collegeids = array();

		if (!empty($collegeid)) {



			$collegeids[] = $collegeid;



		} else {





			$this->loadModel('Tbl_schools');

			if (!empty($key)) {



				$respage = $this->Tbl_schools->find("all", [

					'conditions' => [

						'status' => 1,

						'OR' => [

							'name LIKE' => '%' . $key . '%'

						]

					]

				]);

				$respage->hydrate(false);

				$cmsp = $respage->toArray();

				if (!empty($cmsp)) {

					foreach ($cmsp as $cmspages) {
						$collegeids[] = $cmspages['id'];
					}

				}

			}

		}

		if (!empty($collegeids)) {

			$this->paginate = [

				'limit' => 15,

				'conditions' => [

					'profileType IN' => ['3', '4'],

					'new_request' => 0,

					'OR' => [

						'college_id  IN' => $collegeids,

						'name LIKE' => '%' . $key . '%',

						'email LIKE' => '%' . $key . '%',

						'mobile LIKE' => '%' . $key . '%',

						'state LIKE' => '%' . $key . '%',

						'district LIKE' => '%' . $key . '%',

						'city LIKE' => '%' . $key . '%'

					]

				],

				'order' => ['id' => 'desc']

			];



		} else {

			$this->paginate = [

				'limit' => 15,

				'conditions' => [

					'profileType IN' => ['3', '4'],

					'new_request' => 0,

					'OR' => [

						'name LIKE' => '%' . $key . '%',

						'email LIKE' => '%' . $key . '%',

						'mobile LIKE' => '%' . $key . '%',

						'state LIKE' => '%' . $key . '%',

						'district LIKE' => '%' . $key . '%',

						'city LIKE' => '%' . $key . '%'

					]

				],

				'order' => ['id' => 'desc']

			];

		}

		$cmspage = $this->paginate('Tbl_faculty_members');



		$this->set('viewData', $cmspage);

	}
	public function studentLoginHistory()
	{
		$this->loadModel('Tbl_faculty_members');
		$this->loadModel('Tbl_schools');

		$Admincheckid = $this->request->session()->read("Admincheck.id");
		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");
		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$key = $_GET['search'] ?? '';

		$collegeids = [];

		// collect relevant college_ids from school filter
		if (!empty($collegeid)) {
			$collegeids[] = $collegeid;
		} else {
			if (!empty($key)) {
				$respage = $this->Tbl_schools->find("all", [
					'conditions' => [
						'status' => 1,
						'OR' => [
							'name LIKE' => '%' . $key . '%',
							'state LIKE' => '%' . $key . '%',
							'district LIKE' => '%' . $key . '%',
							'city LIKE' => '%' . $key . '%',
						]
					]
				]);
				$respage->hydrate(false);
				$cmsp = $respage->toArray();

				if (!empty($cmsp)) {
					foreach ($cmsp as $cmspages) {
						$collegeids[] = $cmspages['id'];
					}
				}
			}
		}

		// faculty member base query
		$query = $this->Tbl_faculty_members->find();
		$query->where([
			'profileType IN' => ['3', '4'],
			'new_request' => 0
		]);

		// Keyword filter
		if (!empty($key)) {
			$query->andWhere(function ($exp, $q) use ($key, $collegeids) {
				$orConditions = [
					'name LIKE' => '%' . $key . '%',
					'email LIKE' => '%' . $key . '%',
					'mobile LIKE' => '%' . $key . '%',
					'mobldate LIKE' => '%' . $key . '%',
					'webldate LIKE' => '%' . $key . '%',
				];

				if (!empty($collegeids)) {
					$orConditions['college_id IN'] = $collegeids;
				}

				return $exp->or_($orConditions);
			});
		}

		// Sort by latest of mobldate or webldate
		$query->order([
			$query->newExpr('GREATEST(IFNULL(mobldate, 0), IFNULL(webldate, 0)) DESC'),
			'id' => 'DESC'
		]);

		$this->paginate = ['limit' => 15];

		$cmspage = $this->paginate($query);

		// Collect all unique college_ids from result
		$collegeIdsUsed = array_unique(array_column($cmspage->toArray(), 'college_id'));

		// Get college details in one shot
		$schoolData = $this->Tbl_schools->find('all', [
			'conditions' => ['id IN' => $collegeIdsUsed],
			'fields' => ['id', 'state', 'district', 'city']
		])->hydrate(false)->toArray();


		// Convert to easy map [id => data]
		$schoolMap = [];
		foreach ($schoolData as $school) {
			$schoolMap[$school['id']] = $school;
		}

		foreach ($cmspage as $record) {
			$cid = $record->college_id;



			$state = $schoolMap[$cid]['state'] ?? '';
			$district = $schoolMap[$cid]['district'] ?? '';
			$city = $schoolMap[$cid]['city'] ?? '';

			$record->set('college_state', $state);
			$record->set('college_district', $district);
			$record->set('college_city', $city);

		}




		$this->set('viewData', $cmspage);
	}



	public function studenttempguestAccounts()
	{

		$this->loadModel('Tbl_faculty_members');

		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('college_id' => 0, 'profileType IN' => array('3', '4'))), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function studentguestAccounts()
	{

		$this->loadModel('Tbl_guest_accounts');

		$this->loadModel('Tbl_faculty_members');



		$resguest = $this->Tbl_guest_accounts->find("all");

		$resguest->hydrate(false);

		$cmsguest = $resguest->toArray();

		if (!empty($cmsguest)) {

			$gsid = array();

			foreach ($cmsguest as $cmsguests) {
				$gsid[] = $cmsguests['user_id'];
			}



			$Admincheckid = $this->request->session()->read("Admincheck.id");

			$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

			$membid = $this->request->session()->read("Tbl_faculty_members.id");

			if (!empty($collegeid)) {



				$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'college_id' => $collegeid, 'new_request' => 0, 'id IN' => $gsid)));



			} else {

				$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'id IN' => $gsid)));

			}

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

		} else {

			$cmspage = '';

		}

		$this->set('viewData', $cmspage);

	}



	public function allstudentList()
	{

		$this->loadModel('Tbl_faculty_members');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('new_request' => 0)), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function addeditStudent()
	{

		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Student_exam_scores');

		$this->loadModel('Tbl_schools');







		if (isset($_GET['id'])) {

			$pageid = $_GET['id'];

			$proprecentage = $this->getproprecentage($pageid);

			$this->set('proPrecentage', $proprecentage);



		} else {
			$pageid = '';

			$this->set('proPrecentage', 0);
		}



		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		$respaged = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $cmspage['id'])));

		$respaged->hydrate(false);

		$cmspaged = $respaged->first();

		$this->set('viewDetails', $cmspaged);



		if ($this->request->is('post')) {





			if (!empty($this->request->data['email'])) {

				if (!empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);
				}



				$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();





				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-list');
					die;

				}

			}

			if (empty($this->request->data['id'])) {

				if (!empty($this->request->data['email'])) {

					$cmpatamail = array('email' => $this->request->data['email'], 'status' => 1);

					$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $cmpatamail, 'fields' => array('id')));

					$cmpcorschkmail->hydrate(false);

					$cmpteamcheckmail = $cmpcorschkmail->first();

					if (!empty($cmpteamcheckmail)) {



						$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

						return $this->redirect(webURL . 'student-list');
						die;

					}

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);
				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();



				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-list');
					die;

				}

			}

			if (empty($cmspage['acc_password'])) {

				$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

				$this->request->data['acc_password'] = base64_encode($cpassword);

			} else {
				unset($this->request->data['acc_password']);

				$cpassword = base64_decode($cmspage['acc_password']);
			}



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('member_id', 'id', 'status')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;
			}



			$membdetail = array();

			if (empty($this->request->data['id'])) {

				$this->request->data['parent_id'] = $membid;

				$this->request->data['college_id'] = $collegeId;

			}



			if ($this->request->data['profileType'] == '3' || $this->request->data['profileType'] == '4') {

				$membdetail['course'] = $this->request->data['course'];

				// $membdetail['mode']=$this->request->data['mode'];

				$membdetail['roll_number'] = $this->request->data['roll_number'];

			} else {

				$membdetail['course'] = '';

			}

			if (isset($this->request->data['year'])) {

				if ($this->request->data['year'] != 'Alumni') {
					$membdetail['year'] = $this->request->data['year'];
					$membdetail['is_alumini'] = '';
				} else {
					$membdetail['year'] = $this->request->data['duration'];
					$membdetail['is_alumini'] = 'Alumni';
				}

			}



			if (isset($this->request->data['semester'])) {

				$membdetail['semester'] = $this->request->data['semester'];

			}

			if (isset($this->request->data['topics'])) {

				$membdetail['topics'] = $this->request->data['topics'];

			}



			if (isset($this->request->data['graduation_line'])) {

				$membdetail['graduation_line'] = $this->request->data['graduation_line'];

			}

			if (isset($this->request->data['experience'])) {

				$membdetail['experience'] = $this->request->data['experience'];

			}

			/* 

																					if(isset($this->request->data['edu_type'])){ 

																					 $membdetail['edu_type']=implode(',',$this->request->data['edu_type']);

																					 }*/



			if (isset($this->request->data['lang_know'])) {

				$membdetail['lang_know'] = implode(',', $this->request->data['lang_know']);

			}

			if (isset($this->request->data['edu_year'])) {

				$membdetail['edu_year'] = implode(',', $this->request->data['edu_year']);

			}

			if (isset($this->request->data['edu_passyear'])) {

				$membdetail['edu_passyear'] = implode(',', $this->request->data['edu_passyear']);

			}

			if (isset($this->request->data['edu_percentage'])) {

				$membdetail['edu_percentage'] = implode(',', $this->request->data['edu_percentage']);

			}

			$membdetail['gender'] = $this->request->data['gender'];

			$membdetail['religion'] = $this->request->data['religion'];

			//$membdetail['category']=$this->request->data['category'];

			$membdetail['caste'] = $this->request->data['caste'];



			$membdetail['prof_skills'] = $this->request->data['prof_skills'];

			$membdetail['soft_skills'] = $this->request->data['soft_skills'];



			$membdetail['village'] = $this->request->data['village'];

			$membdetail['tech_skills'] = $this->request->data['tech_skills'];

			$membdetail['hobbies'] = $this->request->data['hobbies'];



			$membdetail['10_board'] = $this->request->data['10_board'];

			$membdetail['10_sc_location'] = $this->request->data['10_sc_location'];

			$membdetail['10_passing_year'] = $this->request->data['10_passing_year'];

			$membdetail['10_aggeregate'] = $this->request->data['10_aggeregate'];

			$membdetail['12_board'] = $this->request->data['12_board'];

			$membdetail['12_sc_location'] = $this->request->data['12_sc_location'];

			$membdetail['12_passing_year'] = $this->request->data['12_passing_year'];

			$membdetail['12_aggeregate'] = $this->request->data['12_aggeregate'];

			$membdetail['admission'] = $this->request->data['admission'];



			$membdetail['pg_board'] = $this->request->data['pg_board'];

			$membdetail['pg_college'] = $this->request->data['pg_college'];

			$membdetail['pg_passing_year'] = $this->request->data['pg_passing_year'];

			$membdetail['pg_aggeregate'] = $this->request->data['pg_aggeregate'];



			if (isset($this->request->data['experience_details'])) {

				$membdetail['experience_details'] = $this->request->data['experience_details'];

			}

			$membdetail['passout'] = $this->request->data['passout'];

			if (!empty($this->request->data['did'])) {

				$membdetail['id'] = $this->request->data['did'];

			}



			unset($this->request->data['did']);

			unset($this->request->data['course']);

			unset($this->request->data['admission']);

			unset($this->request->data['passout']);

			unset($this->request->data['year']);

			unset($this->request->data['semester']);

			unset($this->request->data['edu_year']);

			unset($this->request->data['edu_passyear']);

			unset($this->request->data['edu_percentage']);

			//unset($this->request->data['mode']);  

			unset($this->request->data['gender']);

			unset($this->request->data['roll_number']);

			unset($this->request->data['religion']);

			// unset($this->request->data['category']);  

			unset($this->request->data['caste']);

			unset($this->request->data['crnt_location']);

			unset($this->request->data['village']);

			unset($this->request->data['tech_skills']);

			unset($this->request->data['hobbies']);

			unset($this->request->data['lang_know']);

			unset($this->request->data['10_board']);

			unset($this->request->data['10_sc_location']);

			unset($this->request->data['10_passing_year']);

			unset($this->request->data['10_aggeregate']);

			unset($this->request->data['12_board']);

			unset($this->request->data['12_sc_location']);

			unset($this->request->data['12_passing_year']);

			unset($this->request->data[' 12_aggeregate']);



			if (!empty($this->request->data['image1']['name'])) {

				$image1 = $file = $this->request->data['image1'];

				$image1_name = $file = $this->request->data['image1']['name'];

				$image1_path = $file = $this->request->data['image1']['tmp_name'];

				$save_image1 = time() . $image1_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage1']) && !empty($this->request->data['saveimage1'])) {

					unlink('img/Member/' . $this->request->data['saveimage1']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image1);

				$membdetail['image_1'] = $save_image1;

			}

			unset($this->request->data['image1']);

			unset($this->request->data['saveimage1']);



			if (!empty($this->request->data['image2']['name'])) {

				$image2 = $file = $this->request->data['image2'];

				$image2_name = $file = $this->request->data['image2']['name'];

				$image2_path = $file = $this->request->data['image2']['tmp_name'];

				$save_image2 = time() . $image2_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage2']) && !empty($this->request->data['saveimage2'])) {

					unlink('img/Member/' . $this->request->data['saveimage2']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image2);

				$membdetail['image_2'] = $save_image2;

			}

			unset($this->request->data['image2']);

			unset($this->request->data['saveimage2']);



			if (!empty($this->request->data['image3']['name'])) {

				$image3 = $file = $this->request->data['image3'];

				$image3_name = $file = $this->request->data['image3']['name'];

				$image3_path = $file = $this->request->data['image3']['tmp_name'];

				$save_image3 = time() . $image3_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage3']) && !empty($this->request->data['saveimage3'])) {

					unlink('img/Member/' . $this->request->data['saveimage3']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image3);

				$membdetail['image_3'] = $save_image3;

			}

			unset($this->request->data['image3']);

			unset($this->request->data['saveimage3']);



			if (!empty($this->request->data['image4']['name'])) {

				$image4 = $file = $this->request->data['image4'];

				$image4_name = $file = $this->request->data['image4']['name'];

				$image4_path = $file = $this->request->data['image4']['tmp_name'];

				$save_image4 = time() . $image4_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage4']) && !empty($this->request->data['saveimage4'])) {

					unlink('img/Member/' . $this->request->data['saveimage4']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image4);

				$membdetail['image_4'] = $save_image4;

			}

			unset($this->request->data['image4']);

			unset($this->request->data['saveimage4']);



			$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			if ($this->request->data['profileType'] == '2') {

				$headtitle = "Faculty Member Account Registered Successfully On LifeSet";

			} else if ($this->request->data['profileType'] == '3') {

				$headtitle = "Studnt Member Account Registered Successfully On LifeSet";

			} else if ($this->request->data['profileType'] == '1') {

				$headtitle = "Faculty Head Account Registered Successfully On LifeSet";

			} else {

				$headtitle = "Studnt Account Registered Successfully On LifeSet";

			}





			if (empty($this->request->data['id']) && $this->request->data['status'] == 1) {



				if (isset($this->request->data['college_id'])) {

					$collegeidd = $this->request->data['college_id'];

				} else {

					$collegeidd = $cmspage['college_id'];

				}

				if (!empty($collegeidd)) {









					$to = $datasthed['email'];



				} else {

					$to = $this->request->data['email'];



				}



				$this->accconfirom($this->request->data['mobile'], $cpassword);

				$subject = $headtitle;

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

    					<head>

    					<title>" . $headtitle . "</title>

    					</head>

    					<body>

    					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

    					<tr>

    					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Member Details  :</h4></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>User Id  : " . $this->request->data['member_id'] . " </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address  : " . $this->request->data['address'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

    					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

    					</td>

    					</tr>

    					</table>

    					</body>

    					</html>";

				mail($to, $subject, $body, $headers);

			} else if ($this->request->data['new_request'] == 1 && $cmspage['status'] != 1 && $this->request->data['status'] == 1) {



				if (isset($this->request->data['college_id'])) {

					$collegeidd = $this->request->data['college_id'];

				} else {

					$collegeidd = $cmspage['college_id'];

				}

				if (!empty($collegeidd)) {

					$restdhed = $this->Tbl_faculty_members->find("all", array('conditions' => array('college_id' => $collegeidd, 'profileType' => 1), 'fields' => array('email', 'mobile')));

					$restdhed->hydrate(false);

					$datasthed = $restdhed->first();



					$to = $this->request->data['email'] . ',' . $datasthed['email'];

					//$this->accconfirom($datasthed['mobile'],$cpassword);

				} else {

					$to = $this->request->data['email'];

				}

				$this->accconfirom($this->request->data['mobile'], $cpassword);



				$subject = $headtitle;

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

					<head>

					<title>" . $headtitle . "</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Member Details  :</h4></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>User Id  : " . $this->request->data['member_id'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address  : " . $this->request->data['address'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

					</td>

					</tr>

					</table>

					</body>

					</html>";

				mail($to, $subject, $body, $headers);



			}



			$this->request->data['invite_status'] = 1;

			$this->request->data['ref_status'] = 1;

			$this->request->data['new_request'] = 0;

			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				$checkdatalt = array('mobile' => $this->request->data['mobile']);

				$corschklst = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatalt, 'fields' => array('id')));

				$corschklst->hydrate(false);

				$cmsplst = $corschklst->first();



				$membdetail['acc_id'] = $cmsplst['id'];



				$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

				$this->Tbl_member_details->save($dataToSavedetl);



				$exambdetail = array(

					"id" => $this->request->data['exam_score_id'],

					"std_id" => $cmsplst['id'],

					"exam_name_1" => $this->request->data['exam_name_1'],

					"score_1" => $this->request->data['score_1'],

					"exam_name_2" => $this->request->data['exam_name_2'],

					"score_2" => $this->request->data['score_2'],

					"exam_name_3" => $this->request->data['exam_name_3'],

					"score_3" => $this->request->data['score_3'],

				);

				$dataToexam = $this->Student_exam_scores->newEntity($exambdetail);

				$this->Student_exam_scores->save($dataToexam);

				/* email here for college change */

				/*echo 'old clz id'.$cmspage['college_id'];

																													  echo ' New clz id'. $this->request->data['college_id']; */

				if ($this->request->data['college_id'] != $cmspage['college_id']) {

					//echo 'old clz id'. $cmspage['college_id'];

					//echo ' New clz id'. $this->request->data['college_id'];

					/* email data is here */

					// echo $this->request->data['college_id'];





					/* $respage123 =$this->Tbl_schools->find("all",array('conditions'=>array('id'=>$this->request->data['college_id'])),array('order'=>array('id' =>'DESC')));	

																																			   $respage123->hydrate(false);

																																			   $sch_data2 =  $respage123->toArray();	

																																			   $this->set('viewData3',$sch_data2); */

					//	print_r($sch_data3); die();

					$resmemdtl = $this->Tbl_schools->find("all", array('conditions' => array('id' => $this->request->data['college_id']), 'fields' => 'name'));

					$resmemdtl->hydrate(false);

					$datamemdtl = $resmemdtl->first();



					$to = $cmspage['email'] . ',a9urag@gmail.com';

					$subject = 'Congratulations! Your profile information has been successfully verified';

					$headers = "MIME-Version: 1.0\r\n";

					$headers .= 'From: info@lifeset.co.in' . "\r\n";

					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";





					$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>	A Student Networking Site from Bharat</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $cmspage['name'] . ",</h2>

                              <p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Congratulations! Your profile information has been successfully verified. We have also tagged your college to match your preferences. Now you can tag yourself to a course in which you study.</p>

                               <br/>

                                    <!-- Content Section start here ------------------- -->

							<h2 style='font-size:20px; font-weight:normal;'><b>Institute name -</b> " . $datamemdtl['name'] . "</h2>

                               

                              <p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'><a href='https://lifeset.co.in'>Link of your Profile page</a></h3></p>

                        	 

                         			

<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Regards,</p>

<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Team LifeSet</p>



                         	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in' />

                              

                              </td>

                          </tr>

                          <tr style='background:#ededed; color:#000;'>

                            <td align='center' style='padding:30px'>

                            

                                        <!-- Action Button Section start here ------------------- -->

                            <a href='https://lifeset.co.in/company-dashboard' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>View Dashboard</a></td>

                          </tr>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";



					mail($to, $subject, $body, $headers);





					/* email data is end here */

















				}



				/* email here for college change end here */

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-list');
				die;

			}

		}

		if ($type == "delete") {

			$resmemdtlch = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid), 'fields' => array('id', 'college_id', 'mobile', 'email')));

			$resmemdtlch->hydrate(false);

			$datamemchdtl = $resmemdtlch->first();



			$contentmem = $this->Tbl_faculty_members->get($pageid);

			if ($this->Tbl_faculty_members->delete($contentmem)) {





				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $pageid), 'fields' => 'id'));

				$resmemdtl->hydrate(false);

				$datamemdtl = $resmemdtl->first();

				if (!empty($datamemdtl)) {

					$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

					$this->Tbl_member_details->delete($contentmemdtl);

				}

				/*	$respost =$this->Tbl_posts->find("all",array('conditions'=>array('college_id'=>$datamemchdtl['college_id']),'fields'=>'id'));	

																													 $respost->hydrate(false);

																													 $datapost =  $respost->toArray();

																													 if(!empty($datapost)){

																														 foreach($datapost as $dataposts){

																															 $contentpost = $this->Tbl_posts->get($dataposts['id']);

																															 $this->Tbl_posts->delete($contentpost);

																														 }

																													 }*/



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-list');
				die;

			}

		}

	}

	public function accconfirom($mobile = 0, $password = 0)
	{

		if (!empty($mobile)) {// Message details

			$numbers = array($mobile);

			/*Student account activation message*/

			$message = "Welcome to LifeSet. Your account has been successfully activated. Your Login ID : $mobile and Password : $password .Regards,Team LifeSet";



			$numbers = implode(',', $numbers);

			$this->send_sms_template($numbers, $message);

		}

	}

	public function acc_activeconfirom($mobile = 0, $password = 0, $inviteby = 0)
	{

		if (!empty($mobile)) {// Message details

			$numbers = array($mobile);

			/*Student account activation message*/

			$message = "Welcome to LifeSet. $inviteby has invited you to join this platform to manage T&P Cell. Your Login ID : $mobile and Password : $password. Regards, Team LifeSet  http://lifeset.co.in/";



			$numbers = implode(',', $numbers);

			$this->send_sms_template($numbers, $message);

		}

	}



	public function appuserList()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_member_details');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage1 = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'college_id' => $collegeid, 'acc_token !=' => '')), array('order' => array('id' => 'DESC')));



		} else {

			$respage1 = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'acc_token !=' => '')), array('order' => array('id' => 'DESC')));

		}

		$respage1->hydrate(false);

		$cmspage1 = $respage1->toArray();

		$this->set('viewData', $cmspage1);



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('editviewData', $cmspage);





	}

	public function referralList()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage1 = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'college_id' => $collegeid, 'new_request' => 0, 'referral' => 1)), array('order' => array('id' => 'DESC')));



		} else {

			$respage1 = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'new_request' => 0, 'referral' => 1)), array('order' => array('id' => 'DESC')));

		}

		$respage1->hydrate(false);

		$cmspage1 = $respage1->toArray();

		$this->set('viewData', $cmspage1);



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('editviewData', $cmspage);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['email'])) {

				if (!empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);
				}

				$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'referral-list');
					die;

				}



				if (empty($this->request->data['id'])) {



					$cmpatamail = array('email' => $this->request->data['email'], 'status' => 1);

					$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $cmpatamail, 'fields' => array('id')));

					$cmpcorschkmail->hydrate(false);

					$cmpteamcheckmail = $cmpcorschkmail->first();

					if (!empty($cmpteamcheckmail)) {



						$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

						return $this->redirect(webURL . 'referral-list');
						die;

					}

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);
				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();



				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'referral-list');
					die;

				}

			}

			$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

			$this->request->data['acc_password'] = base64_encode($cpassword);



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('member_id', 'id')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;
			}



			$membdetail = array();

			if (empty($this->request->data['id'])) {

				$this->request->data['parent_id'] = $membid;

				$this->request->data['college_id'] = $collegeId;

			}



			$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			$this->request->data['status'] = 1;

			$this->request->data['referral'] = 1;



			if ($this->request->data['profileType'] == '2') {

				$department = "<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Department  : " . $this->request->data['department'] . " </h3></td>

					</tr>";

			}

			if (empty($this->request->data['id'])) {





				$this->accconfirom($this->request->data['mobile'], $cpassword);



				$to = $this->request->data['email'];



				if ($this->request->data['profileType'] == '2') {

					$headtitle = "Faculty Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '3') {

					$headtitle = "Studnt Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '1') {

					$headtitle = "Faculty Head Account Registered Successfully On LifeSet";

				} else {

					$headtitle = "Studnt Account Registered Successfully On LifeSet";

				}



				$subject = $headtitle;

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

					<head>

					<title>" . $headtitle . "</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Member Details  :</h4></td>

					</tr>

					" . $department . "

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Website Link  : https://lifeset.co.in/  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

					</td>

					</tr>

					</table>

					</body>

					</html>";

				mail($to, $subject, $body, $headers);

			}



			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				$checkdatalt = array('mobile' => $this->request->data['mobile']);

				$corschklst = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatalt, 'fields' => array('id')));

				$corschklst->hydrate(false);

				$cmsplst = $corschklst->first();



				$membdetail['acc_id'] = $cmsplst['id'];

				$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

				$this->Tbl_member_details->save($dataToSavedetl);



				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'referral-list');
				die;



			}

		}

		if ($type == "delete") {

			$resmemdtlch = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid), 'fields' => array('id', 'college_id', 'email', 'mobile')));

			$resmemdtlch->hydrate(false);

			$datamemchdtl = $resmemdtlch->first();



			$contentmem = $this->Tbl_faculty_members->get($pageid);

			if ($this->Tbl_faculty_members->delete($contentmem)) {



				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $pageid), 'fields' => 'id'));

				$resmemdtl->hydrate(false);

				$datamemdtl = $resmemdtl->first();

				if (!empty($datamemdtl)) {

					$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

					$this->Tbl_member_details->delete($contentmemdtl);

				}

				/*	$respost =$this->Tbl_posts->find("all",array('conditions'=>array('college_id'=>$datamemchdtl['college_id']),'fields'=>'id'));	

																													 $respost->hydrate(false);

																													 $datapost =  $respost->toArray();

																													 if(!empty($datapost)){

																														 foreach($datapost as $dataposts){

																															 $contentpost = $this->Tbl_posts->get($dataposts['id']);

																															 $this->Tbl_posts->delete($contentpost);

																														 }

																													 }*/



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'referral-list');
				die;

			}

		}

	}

	public function invitememberList()
	{

		$this->loadModel('Tbl_faculty_members');



		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'college_id' => $collegeid, 'new_request' => 0, 'invite' => 1)), array('order' => array('id' => 'DESC')));



		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('2', '3', '4'), 'new_request' => 0, 'invite' => 1)), array('order' => array('id' => 'DESC')));

		}

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);





	}

	public function inviteMember()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_posts');

		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['email'])) {

				if (!empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);
				}

				$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-list');
					die;

				}



				if (empty($this->request->data['id'])) {



					$cmpatamail = array('email' => $this->request->data['email'], 'status' => 1);

					$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $cmpatamail, 'fields' => array('id')));

					$cmpcorschkmail->hydrate(false);

					$cmpteamcheckmail = $cmpcorschkmail->first();

					if (!empty($cmpteamcheckmail)) {



						$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

						return $this->redirect(webURL . 'student-list');
						die;

					}

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);
				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();



				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-list');
					die;

				}

			}

			$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

			$this->request->data['acc_password'] = base64_encode($cpassword);



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('member_id', 'id')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'LIFE-SET-' . $newid;
			}



			$membdetail = array();

			if (empty($this->request->data['id'])) {

				$this->request->data['parent_id'] = $membid;

				$this->request->data['college_id'] = $collegeId;

			}



			$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			$this->request->data['status'] = 1;



			if ($this->request->data['profileType'] == '2') {

				$department = "<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Department  : " . $this->request->data['department'] . " </h3></td>

					</tr>";

			}



			if (empty($this->request->data['id'])) {



				$invitedata = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $membid), 'fields' => array('name')));

				$invitedata->hydrate(false);

				$inviteby = $invitedata->first();



				$this->acc_activeconfirom($this->request->data['mobile'], $cpassword, $inviteby['name']);

				if (!empty($this->request->data['email'])) {

					$to = $this->request->data['email'];



					if ($this->request->data['profileType'] == '2') {

						$headtitle = "Faculty Member Account Registered Successfully On LifeSet";

					} else if ($this->request->data['profileType'] == '3') {

						$headtitle = "Studnt Member Account Registered Successfully On LifeSet";

					} else if ($this->request->data['profileType'] == '1') {

						$headtitle = "Faculty Head Account Registered Successfully On LifeSet";

					} else {

						$headtitle = "Studnt Account Registered Successfully On LifeSet";

					}



					$subject = $headtitle;

					$headers = "MIME-Version: 1.0\r\n";

					$headers .= 'From: info@lifeset.co.in' . "\r\n";

					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

					$body = "<html>

    					<head>

    					<title>" . $headtitle . "</title>

    					</head>

    					<body>

    					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

    					<tr>

    					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Member Details  :</h4></td>

    					</tr>

    					" . $department . "

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Website Link  : https://lifeset.co.in/  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

    					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

    					</td>

    					</tr>

    					</table>

    					</body>

    					</html>";

					mail($to, $subject, $body, $headers);

				}



			}



			$this->request->data['invite'] = 1;



			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {



				$checkdatalt = array('mobile' => $this->request->data['mobile']);

				$corschklst = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatalt, 'fields' => array('id')));

				$corschklst->hydrate(false);

				$cmsplst = $corschklst->first();



				$membdetail['acc_id'] = $cmsplst['id'];

				$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

				$this->Tbl_member_details->save($dataToSavedetl);



				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'invite-member-list');
				die;



			}

		}



		if ($type == "delete") {

			$resmemdtlch = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid), 'fields' => array('id', 'college_id', 'mobile', 'email')));

			$resmemdtlch->hydrate(false);

			$datamemchdtl = $resmemdtlch->first();



			$contentmem = $this->Tbl_faculty_members->get($pageid);

			if ($this->Tbl_faculty_members->delete($contentmem)) {



				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $pageid), 'fields' => 'id'));

				$resmemdtl->hydrate(false);

				$datamemdtl = $resmemdtl->first();

				if (!empty($datamemdtl)) {

					$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

					$this->Tbl_member_details->delete($contentmemdtl);

				}

				/*	$respost =$this->Tbl_posts->find("all",array('conditions'=>array('college_id'=>$datamemchdtl['college_id']),'fields'=>'id'));	

																													 $respost->hydrate(false);

																													 $datapost =  $respost->toArray();

																													 if(!empty($datapost)){

																														 foreach($datapost as $dataposts){

																															 $contentpost = $this->Tbl_posts->get($dataposts['id']);

																															 $this->Tbl_posts->delete($contentpost);

																														 }

																													 }*/



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'invite-member-list');
				die;

			}

		}

	}



	public function addeditMember()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_company_accs');

		if (isset($_GET['id'])) {

			$pageid = $_GET['id'];

			$proprecentage = $this->getproprecentage($pageid);

			$this->set('proPrecentage', $proprecentage);



		} else {
			$pageid = '';

			$this->set('proPrecentage', 0);
		}



		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		$respaged = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $cmspage['id'])));

		$respaged->hydrate(false);

		$cmspaged = $respaged->first();

		$this->set('viewDetails', $cmspaged);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['email'])) {

				if (!empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id'], 'status' => 1);

				} else {
					$checkdatamail = array('email' => $this->request->data['email'], 'status' => 1);
				}

				$corschkmail = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatamail, 'fields' => array('member_id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'member-list');
					die;

				}



				if (empty($this->request->data['id'])) {



					$cmpatamail = array('email' => $this->request->data['email'], 'status' => 1);

					$cmpcorschkmail = $this->Tbl_company_accs->find("all", array("conditions" => $cmpatamail, 'fields' => array('id')));

					$cmpcorschkmail->hydrate(false);

					$cmpteamcheckmail = $cmpcorschkmail->first();

					if (!empty($cmpteamcheckmail)) {



						$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

						return $this->redirect(webURL . 'member-list');
						die;

					}

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);
				} else {
					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);
				}

				$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('member_id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();



				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'member-list');
					die;

				}

			}

			$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

			$this->request->data['acc_password'] = base64_encode($cpassword);



			$corschkprf = $this->Tbl_faculty_members->find("all", array('order' => array('id' => 'DESC'), 'fields' => array('member_id', 'id')));

			$corschkprf->hydrate(false);

			$teamcheckprf = $corschkprf->first();

			if (!empty($teamcheckprf)) {

				$newid1 = $teamcheckprf['id'] + 1;

				$newid = str_pad($newid1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;

			} else {

				$newid = str_pad(1, 2, "0", STR_PAD_LEFT);

				$this->request->data['member_id'] = 'SCH-PTL-' . $newid;
			}



			$membdetail = array();

			if (empty($this->request->data['id'])) {

				$this->request->data['parent_id'] = $membid;

				$this->request->data['college_id'] = $collegeId;

			}



			if ($this->request->data['profileType'] == '3' || $this->request->data['profileType'] == '4') {

				$membdetail['course'] = $this->request->data['course'];

				$membdetail['roll_number'] = $this->request->data['roll_number'];

			} else {

				$membdetail['course'] = '';

			}

			if (isset($this->request->data['year'])) {

				if ($this->request->data['year'] != 'Alumni') {
					$membdetail['year'] = $this->request->data['year'];
					$membdetail['is_alumini'] = '';
				} else {
					$membdetail['year'] = $this->request->data['duration'];
					$membdetail['is_alumini'] = 'Alumni';
				}

			}

			if (isset($this->request->data['semester'])) {

				$membdetail['semester'] = $this->request->data['semester'];

			}

			if (isset($this->request->data['lang_know'])) {

				$membdetail['lang_know'] = implode(',', $this->request->data['lang_know']);

			}

			if (isset($this->request->data['edu_year'])) {

				$membdetail['edu_year'] = implode(',', $this->request->data['edu_year']);

			}

			if (isset($this->request->data['edu_passyear'])) {

				$membdetail['edu_passyear'] = implode(',', $this->request->data['edu_passyear']);

			}

			if (isset($this->request->data['edu_percentage'])) {

				$membdetail['edu_percentage'] = implode(',', $this->request->data['edu_percentage']);

			}

			$membdetail['gender'] = $this->request->data['gender'];

			$membdetail['religion'] = $this->request->data['religion'];

			$membdetail['caste'] = $this->request->data['caste'];



			$membdetail['village'] = $this->request->data['village'];

			$membdetail['tech_skills'] = $this->request->data['tech_skills'];

			$membdetail['hobbies'] = $this->request->data['hobbies'];



			$membdetail['10_board'] = $this->request->data['10_board'];

			$membdetail['10_sc_location'] = $this->request->data['10_sc_location'];

			$membdetail['10_passing_year'] = $this->request->data['10_passing_year'];

			$membdetail['10_aggeregate'] = $this->request->data['10_aggeregate'];

			$membdetail['12_board'] = $this->request->data['12_board'];

			$membdetail['12_sc_location'] = $this->request->data['12_sc_location'];

			$membdetail['12_passing_year'] = $this->request->data['12_passing_year'];

			$membdetail['12_aggeregate'] = $this->request->data['12_aggeregate'];

			$membdetail['admission'] = $this->request->data['admission'];

			$membdetail['passout'] = $this->request->data['passout'];

			if (!empty($this->request->data['did'])) {

				$membdetail['id'] = $this->request->data['did'];

			}

			unset($this->request->data['did']);

			unset($this->request->data['course']);

			unset($this->request->data['admission']);

			unset($this->request->data['passout']);

			unset($this->request->data['year']);

			unset($this->request->data['semester']);

			unset($this->request->data['edu_year']);

			unset($this->request->data['edu_passyear']);

			unset($this->request->data['edu_percentage']);

			unset($this->request->data['gender']);

			unset($this->request->data['roll_number']);

			unset($this->request->data['religion']);

			// unset($this->request->data['category']);  

			unset($this->request->data['caste']);

			unset($this->request->data['crnt_location']);

			unset($this->request->data['village']);

			unset($this->request->data['tech_skills']);

			unset($this->request->data['hobbies']);

			unset($this->request->data['lang_know']);

			unset($this->request->data['10_board']);

			unset($this->request->data['10_sc_location']);

			unset($this->request->data['10_passing_year']);

			unset($this->request->data['10_aggeregate']);

			unset($this->request->data['12_board']);

			unset($this->request->data['12_sc_location']);

			unset($this->request->data['12_passing_year']);

			unset($this->request->data[' 12_aggeregate']);



			if (!empty($this->request->data['image1']['name'])) {

				$image1 = $file = $this->request->data['image1'];

				$image1_name = $file = $this->request->data['image1']['name'];

				$image1_path = $file = $this->request->data['image1']['tmp_name'];

				$save_image1 = time() . $image1_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage1']) && !empty($this->request->data['saveimage1'])) {

					unlink('img/Member/' . $this->request->data['saveimage1']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image1);

				$membdetail['image_1'] = $save_image1;

			}

			unset($this->request->data['image1']);

			unset($this->request->data['saveimage1']);



			if (!empty($this->request->data['image2']['name'])) {

				$image2 = $file = $this->request->data['image2'];

				$image2_name = $file = $this->request->data['image2']['name'];

				$image2_path = $file = $this->request->data['image2']['tmp_name'];

				$save_image2 = time() . $image2_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage2']) && !empty($this->request->data['saveimage2'])) {

					unlink('img/Member/' . $this->request->data['saveimage2']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image2);

				$membdetail['image_2'] = $save_image2;

			}

			unset($this->request->data['image2']);

			unset($this->request->data['saveimage2']);



			if (!empty($this->request->data['image3']['name'])) {

				$image3 = $file = $this->request->data['image3'];

				$image3_name = $file = $this->request->data['image3']['name'];

				$image3_path = $file = $this->request->data['image3']['tmp_name'];

				$save_image3 = time() . $image3_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage3']) && !empty($this->request->data['saveimage3'])) {

					unlink('img/Member/' . $this->request->data['saveimage3']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image3);

				$membdetail['image_3'] = $save_image3;

			}

			unset($this->request->data['image3']);

			unset($this->request->data['saveimage3']);



			if (!empty($this->request->data['image4']['name'])) {

				$image4 = $file = $this->request->data['image4'];

				$image4_name = $file = $this->request->data['image4']['name'];

				$image4_path = $file = $this->request->data['image4']['tmp_name'];

				$save_image4 = time() . $image4_name;

				if (file_exists('img/Member/' . $this->request->data['saveimage4']) && !empty($this->request->data['saveimage4'])) {

					unlink('img/Member/' . $this->request->data['saveimage4']);

				}

				@move_uploaded_file($file, "img/Member/" . $save_image4);

				$membdetail['image_4'] = $save_image4;

			}

			unset($this->request->data['image4']);

			unset($this->request->data['saveimage4']);



			$this->request->data['regdate'] = date('Y-m-d h:i:s a');

			if (empty($this->request->data['id']) && !empty($this->request->data['profileType'] != '3')) {

				$to = $this->request->data['email'];



				if ($this->request->data['profileType'] == '2') {

					$headtitle = "Faculty Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '3') {

					$headtitle = "Studnt Member Account Registered Successfully On LifeSet";

				} else if ($this->request->data['profileType'] == '1') {

					$headtitle = "Faculty Head Account Registered Successfully On LifeSet";

				}

				$subject = $headtitle;

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>	A Student Networking Site from Bharat</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $data['name'] . ",</h2>

                             

                                    <!-- Content Section start here ------------------- -->

                              

                              <p style='padding:20px 50px; font-size:18px; line-height:23px font-weight:bold;'>Student Details</p>

                              <p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name : " . $this->request->data['name'] . " </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>User Id : " . $this->request->data['member_id'] . "  </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name : " . $this->request->data['name'] . "  </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email ID : " . $this->request->data['email'] . "  </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number : " . $this->request->data['mobile'] . "  </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password : " . $cpassword . "  </p>

                        					<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address : " . $this->request->data['address'] . "  </p>

                        					

                        	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in' />

                              

                              </td>

                          </tr>

                          <tr style='background:#ededed; color:#000;'>

                            <td align='center' style='padding:30px'>

                            

                                        <!-- Action Button Section start here ------------------- -->

                            <a href='https://lifeset.co.in/login' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>Login</a></td>

                          </tr>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";

				mail($to, $subject, $body, $headers);

			}

			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				$checkdatalt = array('mobile' => $this->request->data['mobile']);

				$corschklst = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdatalt, 'fields' => array('id')));

				$corschklst->hydrate(false);

				$cmsplst = $corschklst->first();



				$membdetail['acc_id'] = $cmsplst['id'];

				$dataToSavedetl = $this->Tbl_member_details->newEntity($membdetail);

				$this->Tbl_member_details->save($dataToSavedetl);



				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-list');
				die;

			}

		}

		if ($type == "delete") {

			$resmemdtlch = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $pageid), 'fields' => array('id', 'college_id', 'mobile', 'email')));

			$resmemdtlch->hydrate(false);

			$datamemchdtl = $resmemdtlch->first();



			$contentmem = $this->Tbl_faculty_members->get($pageid);

			if ($this->Tbl_faculty_members->delete($contentmem)) {



				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $pageid), 'fields' => 'id'));

				$resmemdtl->hydrate(false);

				$datamemdtl = $resmemdtl->first();

				if (!empty($datamemdtl)) {

					$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

					$this->Tbl_member_details->delete($contentmemdtl);

				}

				/*	$respost =$this->Tbl_posts->find("all",array('conditions'=>array('college_id'=>$datamemchdtl['college_id']),'fields'=>'id'));	

																													 $respost->hydrate(false);

																													 $datapost =  $respost->toArray();

																													 if(!empty($datapost)){

																														 foreach($datapost as $dataposts){

																															 $contentpost = $this->Tbl_posts->get($dataposts['id']);

																															 $this->Tbl_posts->delete($contentpost);

																														 }

																													 }*/



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-list');
				die;

			}

		}

	}

	public function thanks()
	{



	}

	public function getTextbox()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_teams');



		if ($this->request->is('ajax')) {

			$html = '';

			$length = $this->request->data['length'];

			for ($i = 1; $i <= $length; $i++) {

				$html .= '<div class="form-group col-md-4"><label class="col-md-12" for="val-username">' . $i . ' Year Seats<span class="text-danger">*</span></label><div class="col-md-12"><input type="number" required  required="required" id="val-name" min="1" name="total_seats[]" value="" class="form-control" placeholder=""></div></div>';

			}

			print_r($html);
			die;

		}

	}

	public function checkMobile()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_teams');



		if ($this->request->is('ajax')) {

			$checkdata = array('mobile' => $this->request->data['mobile']);

			$corschk = $this->Tbl_teams->find("all", array("conditions" => $checkdata));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();



			if (!empty($teamcheck)) {

				echo '1';

			} else {

				echo '2';

			}

			$this->autoRender = false;

			exit();

		}

	}

	public function getlocation()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_locations');



		if ($this->request->is('ajax')) {



			$checkdata = array('pincode' => $this->request->data['pin'], 'status' => '1');

			$corschk = $this->Tbl_locations->find("all", array('conditions' => $checkdata));

			$corschk->hydrate(false);

			$dtacheck = $corschk->first();

			if (!empty($dtacheck)) {

				echo '<div class="form-group col-md-4">

                                            <label class="col-md-12" for="val-username">State<span class="text-danger">*</span></label>

                                            <div class="col-md-12">

                                                <input type="text" id="val-name" required  name="state"  value="' . $dtacheck['state'] . '" class="form-control" placeholder="">

                                            </div>

                                        </div>

										<div class="form-group col-md-4">

                                            <label class="col-md-12" for="val-username">District<span class="text-danger">*</span></label>

                                            <div class="col-md-12">

                                                <input type="text" id="val-name" required name="district"  value="' . $dtacheck['district'] . '" class="form-control" placeholder="">

                                            </div>

                                        </div>';
				die;

			} else {

				echo '<div class="form-group col-md-4">

                                            <label class="col-md-12" for="val-username">State<span class="text-danger">*</span></label>

                                            <div class="col-md-12">

                                                <input type="text" id="val-name" required  name="state"  value="" class="form-control" placeholder="">

                                            </div>

                                        </div>

										<div class="form-group col-md-4">

                                            <label class="col-md-12" for="val-username">District<span class="text-danger">*</span></label>

                                            <div class="col-md-12">

                                                <input type="text" id="val-name" required name="district"  value="" class="form-control" placeholder="">

                                            </div>

                                        </div>';
				die;

			}

		}

	}

	public function getcoursedtail()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_courses');



		if ($this->request->is('ajax')) {

			$checkdata = array('id' => $this->request->data['course']);

			$corschk = $this->Tbl_courses->find("all", array("conditions" => $checkdata));

			$corschk->hydrate(false);

			$dtacheck = $corschk->first();

			$course = '';

			$yeardtl = '';

			$optionyear = '';

			if (!empty($dtacheck)) {



				for ($ny = date('Y'); $ny > 2000; $ny--) {

					$optionyear .= '<option value="' . $ny . '">' . $ny . '</option>';

				}

				$course .= '<div class="form-group col-md-3">

												<label class="col-md-12" for="val-username">&nbsp;<span class="text-danger"></span></label>

												<div class="col-md-12">

													' . $dtacheck['duration'] . ' Year Course

												</div><br>&nbsp;&nbsp;

											</div>

											<div class="form-group col-md-3">

												<label class="col-md-12" for="val-username">&nbsp; <span class="text-danger"></span></label>

												<div class="col-md-12">

												   ' . $dtacheck['semester'] . ' Semester

												</div><br>&nbsp;&nbsp;

											</div><input type="hidden" name="year" value="' . $dtacheck['duration'] . '" class="form-control" placeholder=""><input type="hidden" name="semester" value="' . $dtacheck['semester'] . '" class="form-control" placeholder="">';

				for ($yi = 1; $yi <= $dtacheck['duration']; $yi++) {

					$yeardtl .= '<div class="row"><div class="allday"> <div class="form-group col-md-3"><label class="col-md-12" for="val-username">Year<span class="text-danger">*</span></label><div class="col-md-12"> <input type="hidden" id="val-name" name="edu_year[]" value="' . $yi . '" readonly class="form-control" placeholder="">' . $yi . '</div></div> <div class="form-group col-md-3"><label class="col-md-12" for="val-skill"> Passing Year <span class="text-danger">*</span></label><div class="col-md-12"><select id="val-type" name="edu_passyear[]" class="form-control">  <option value=""  >Select</option>' . $optionyear . '</select></div></div>    <div class="form-group col-md-3"><label class="col-md-12" for="val-username">Percentage <span class="text-danger">*</span></label><div class="col-md-12"><input type="text" id="val-name" name="edu_percentage[]" max="100" min="0" value="" class="form-control" placeholder=""> </div> </div> </div></div>';

				}







				$response = array('course' => $course, 'year' => $yeardtl, 'level' => $dtacheck['level']);

				print_r(json_encode($response));
				die;

			} else {

				echo '';
				die;

			}

			$this->autoRender = false;

			exit();

		}



	}

	public function collegeStudentList($id = 0)
	{

		$this->loadModel('Tbl_faculty_members');

		$checkdata = array('id' => base64_decode($id), 'new_request' => 0);

		$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata), array('order' => array('id' => 'DESC')));

		$corschk->hydrate(false);

		$teamcheck = $corschk->toArray();

		$this->set('viewdata', $teamcheck);

	}

	public function collegeDashboard($id = 0)
	{

		$this->loadModel('Tbl_schools');

		$checkdata = array('id' => base64_decode($id), 'new_request' => 0);

		$corschk = $this->Tbl_schools->find("all", array("conditions" => $checkdata));

		$corschk->hydrate(false);

		$teamcheck = $corschk->first();

		$this->set('viewschData', $teamcheck);

	}

	public function getproprecentage($id = 0)
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$checkdata = array('id' => $id, 'new_request' => 0);

		$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata));

		$corschk->hydrate(false);

		$teamcheck = $corschk->first();

		$profilepercent = array();

		if (!empty($teamcheck)) {



			$name = $teamcheck['name'];

			$email = $teamcheck['email'];

			$mobile = $teamcheck['mobile'];

			$state = $teamcheck['state'];

			$district = $teamcheck['district'];

			$city = $teamcheck['city'];

			$pincode = $teamcheck['pincode'];

			$address = $teamcheck['address'];

			if (!empty($name) && !empty($email) && !empty($mobile) && !empty($state) && !empty($district) && !empty($city) && !empty($pincode) && !empty($address)) {
				$profilepercent[] = 20;
			}



			$checkdatachk = array('acc_id' => $id);

			$corschkdtl = $this->Tbl_member_details->find("all", array("conditions" => $checkdatachk));

			$corschkdtl->hydrate(false);

			$teamcheckdtl = $corschkdtl->first();

			if (!empty($teamcheckdtl)) {



				$year = $teamcheckdtl['year'];

				$semester = $teamcheckdtl['semester'];

				$mode = $teamcheckdtl['mode'];

				$gender = $teamcheckdtl['gender'];

				$roll_number = $teamcheckdtl['roll_number'];

				$religion = $teamcheckdtl['religion'];

				$caste = $teamcheckdtl['caste'];



				if (!empty($year) && !empty($semester) && !empty($gender) && !empty($roll_number) && !empty($religion) && !empty($caste)) {
					$profilepercent[] = 20;
				}



				//$edu_type=$teamcheckdtl['edu_type'];

				$edu_year = $teamcheckdtl['edu_year'];

				$edu_passyear = $teamcheckdtl['edu_passyear'];

				$edu_percentage = $teamcheckdtl['edu_percentage'];



				if (!empty($edu_year) && !empty($edu_passyear)) {
					$profilepercent[] = 10;
				}



				$board_10 = $teamcheckdtl['10_board'];

				$sc_location_10 = $teamcheckdtl['10_sc_location'];

				$passing_year_10 = $teamcheckdtl['10_passing_year'];

				$aggeregate_10 = $teamcheckdtl['10_aggeregate'];



				if (!empty($board_10) && !empty($sc_location_10) && !empty($passing_year_10) && !empty($aggeregate_10)) {
					$profilepercent[] = 10;
				}



				$board_12 = $teamcheckdtl['12_board'];

				$sc_location_12 = $teamcheckdtl['12_sc_location'];

				$passing_year_12 = $teamcheckdtl['12_passing_year'];

				$aggeregate_12 = $teamcheckdtl['12_aggeregate'];



				if (!empty($board_12) && !empty($sc_location_12) && !empty($passing_year_12) && !empty($aggeregate_12)) {
					$profilepercent[] = 10;
				}



				$image_1 = $teamcheckdtl['image_1'];

				if (!empty($image_1)) {
					$profilepercent[] = 10;
				}



				$tech_skills = $teamcheckdtl['tech_skills'];

				$hobbies = $teamcheckdtl['hobbies'];

				$lang_know = $teamcheckdtl['lang_know'];



				if (!empty($tech_skills) && !empty($hobbies) && !empty($lang_know)) {
					$profilepercent[] = 10;
				}



			}

			return array_sum($profilepercent);
			die;

		} else {

			return 0;
			die;

		}



	}



	public function viewstreamcourseList()
	{

		$this->loadModel('Tbl_courses');

		if (isset($_GET['cs'])) {
			$pageid = $_GET['cs'];
		} else {
			$pageid = '';
		}



		$checkdata = array('id' => base64_decode($pageid));

		$corschk = $this->Tbl_courses->find("all", array("conditions" => $checkdata));

		$corschk->hydrate(false);

		$teamcheck = $corschk->first();

		$this->set('viewcourse', $teamcheck);



	}



	public function viewStreamSheets()
	{

		$this->loadModel('Tbl_courses');

		$this->loadModel('Tbl_member_details');

		if (isset($_GET['cs'])) {
			$pageid = $_GET['cs'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['st'])) {
			$st = $_GET['st'];
		} else {
			$st = '';
		}



		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($collegeid)) {

			$checkdata = array('stream_title' => base64_decode($st), 'name' => base64_decode($pageid), 'college_id' => $collegeid);

		} else {

			$checkdata = array('stream_title' => base64_decode($st), 'name' => base64_decode($pageid));

		}

		$corschk = $this->Tbl_courses->find("all", array("conditions" => $checkdata, 'fields' => array('id', 'total_seats', 'college_id')), array('order' => array('id' => 'DESC')));

		$corschk->hydrate(false);

		$teamcheck = $corschk->toArray();



		$this->set('viewdata', $teamcheck);

		$this->set('viewstream', base64_decode($st));

		$this->set('viewscrs', base64_decode($pageid));

	}



	public function sendremarks()
	{

		if ($this->request->is('post')) {
			$this->loadModel('Tbl_faculty_members');

			$mailby = $this->request->data['mailby'];

			$student_name = $this->request->data['student'];

			$sid = explode('#', $this->request->data['sid']);



			$checkdata = array('id' => $sid[1]);

			$corschk = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('mobile')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			$remarks = $this->request->data['remarks'];

			$mobile = $teamcheck['mobile'];

			if (isset($this->request->data['weburl'])) {

				$responseurl = $this->request->data['weburl'];

				$to = $teamcheck['email'];

				$subject = "Remarks from $mailby - LifeSet";

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

					<head>

					<title>Remarks from $mailby - LifeSet</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Remarks  : " . $remarks . " </h3></td>

					</tr>

					</table>

					</body>

					</html>";

				if (mail($to, $subject, $body, $headers)) {

					$this->Flash->success('Remarks successfully send', array('key' => 'rems'));

				}

				return $this->redirect('' . $responseurl . '');
				die;

			} else {



				return $this->redirect('https://api.whatsapp.com/send?phone=91' . $mobile . '&text=' . $remarks);
				die;



			}



		}



	}

	/*--- company ---*/

	public function companyList()
	{

		$this->loadModel('Tbl_company_accs');

		$respage = $this->Tbl_company_accs->find("all", array('conditions' => array('new_request' => 0)), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}

	public function addEditCompany()
	{

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Member_credit_points');

		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$respage = $this->Tbl_company_accs->find("all", array('conditions' => array('id' => $pageid), 'new_request' => 0));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			if (empty($this->request->data['id'])) {

				$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);

				$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

				$this->request->data['acc_password'] = base64_encode($cpassword);

			} else {

				$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

			}

			$corschk = $this->Tbl_company_accs->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (empty($teamcheck)) {

				if ($this->request->data['type'] == 'Live') {

					$this->request->data['status'] = 1;

				} else {

					$this->request->data['status'] = 0;

				}

				$dataToSave = $this->Tbl_company_accs->newEntity($this->request->data);

				if ($this->Tbl_company_accs->save($dataToSave)) {

					if (isset($this->request->data['credit_points']) && !empty($this->request->data['credit_points'])) {

						$corsc = $this->Tbl_company_accs->find("all", array("conditions" => array('mobile' => $this->request->data['mobile']), 'fields' => array('id')));

						$corsc->hydrate(false);

						$teamnew = $corsc->first();



						$savecreadit = array(

							//"id"=>$this->request->data['credit_id'],

							"cmp_id" => $teamnew['id'],

							"total_point" => $this->request->data['credit_points'],

							"added_date" => date('Y-m-d'),

							"per_std_point" => 50,

						);

						$dataTocredit = $this->Member_credit_points->newEntity($savecreadit);

						$this->Member_credit_points->save($dataTocredit);

					}



					if ($this->request->data['mail_status'] == 0) {





						$to = webEmail . ',' . $this->request->data['email'];

						$subject = "Company Account Registered Successfully On LifeSet";

						$headers = "MIME-Version: 1.0\r\n";

						$headers .= 'From: info@lifeset.co.in' . "\r\n";

						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

						$body = "<html>

					<head>

					<title>Company Account Registered Successfully On LifeSet</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Details  :</h4></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Pincode  : " . $this->request->data['pincode'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>State  : " . $this->request->data['state'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>District  : " . $this->request->data['district'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>City  : " . $this->request->data['city'] . "  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address  : " . $this->request->data['address'] . "  </h3></td>

					</tr>

					</table>

					</body>

					</html>";

						$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>Company Account Registered Successfully On LifeSet</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $this->request->data['name'] . ",</h2>

                              <h2 style='font-size:30px; font-weight:normal;'>Thank you for registering with LifeSet, the World’s 1st Students’ Networking Platform.</h2>

                             

                              

                                    <!-- Content Section start here ------------------- -->

                              <p style='font-size:26px; font-weight:normal;'>Here you can access students' profiles and contact compatible candidates to meet your hiring needs.</p>

                                                          

                              

                    		<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </p>

							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </p>

							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "</p>

  							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "</p>

                        					

                        	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in/cmp-profile' />

                              

                              </td>

                          </tr>

                          <tr style='background:#ededed; color:#000;'>

                            <td align='center' style='padding:30px'>

                            

                                        <!-- Action Button Section start here ------------------- -->

                            <a href='https://lifeset.co.in/login' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>Login Now!</a></td>

                          </tr>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";

						mail($to, $subject, $body, $headers);



						$updtdt['id'] = $dataToSave['id'];

						$updtdt['mail_status'] = 1;

						$dataupdtToSave = $this->Tbl_company_accs->newEntity($updtdt);

						$this->Tbl_company_accs->save($dataupdtToSave);



					}

					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'admin-company-list');
					die;

				}

			} else {

				$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'add-edit-company');
				die;

			}

		}
		if ($type == "delete") {

			$contentmem = $this->Tbl_company_accs->get($pageid);

			if ($this->Tbl_company_accs->delete($contentmem)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'admin-company-list');
				die;

			}

		}

	}



	public function cmpProfile()
	{

		$this->loadModel('Tbl_company_accs');

		$membid = $this->request->session()->read("company_accs.id");

		$respage = $this->Tbl_company_accs->find("all", array('conditions' => array('id' => $membid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);

		if ($this->request->is('post')) {



			$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

			$corschk = $this->Tbl_company_accs->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (empty($teamcheck)) {



				if (!empty($this->request->data['image1']['name'])) {

					$image1 = $file = $this->request->data['image1'];

					$image1_name = $file = $this->request->data['image1']['name'];

					$image1_path = $file = $this->request->data['image1']['tmp_name'];

					$save_image1 = time() . $image1_name;

					if (file_exists('img/Profile/' . $this->request->data['saveimage1']) && !empty($this->request->data['saveimage1'])) {

						unlink('img/Profile/' . $this->request->data['saveimage1']);

					}

					@move_uploaded_file($file, "img/Profile/" . $save_image1);

					$this->request->data['profile'] = $save_image1;

				}

				unset($this->request->data['image1']);

				unset($this->request->data['saveimage1']);

				if (!isset($this->request->data['acc_status'])) {

					$this->request->data['status'] = 0;
				} else {
					$this->request->data['status'] = 1;
				}

				$dataToSave = $this->Tbl_company_accs->newEntity($this->request->data);

				if ($this->Tbl_company_accs->save($dataToSave)) {



					$this->Flash->success('Data successfully update', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'cmp-profile');
					die;

				}

			} else {

				$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'cmp-profile');
				die;

			}

		}

	}



	public function memberPasswordReminder()
	{

		$this->viewBuilder()->layout('');

		if ($this->request->is('post')) {

			$this->loadModel('Tbl_faculty_members');

			$reminderemail = $this->request->data['reminderemail'];

			$checkdata = array("email" => $reminderemail);

			$adminres = $this->Tbl_faculty_members->find("all", array("conditions" => $checkdata, 'fields' => array('acc_password', 'email')));

			$adminres->hydrate(false);

			$adminData = $adminres->toArray();

			if (!empty($adminData)) {

				$remEmail = $adminData['email'];

				$remPassword = base64_decode($adminData['acc_password']);

				$to = $reminderemail;

				$subject = "Password Reminder";

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

			<head>

			<title>Password Reminder</title>

			</head>

			<body>

			<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

			<tr>

			<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Login  :</h4></td>

			</tr>

			<tr>

			<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email Id  : $remEmail  </h3></td>

			</tr>

			<tr>

			<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : $remPassword  </h3></td>

			</tr>

			</table>

			</body>

			</html>";



				if ($response = mail($to, $subject, $body, $headers)) {

					$this->Flash->set('Password send in your email id');

					return $this->redirect(webURL . 'member-login');
					die;

				}

			} else {

				$this->Flash->set('Invalid email id');

				return $this->redirect(webURL . 'member-password-reminder');
				die;

			}

		}

	}

	public function companyPasswordReminder()
	{

		$this->viewBuilder()->layout('');

		if ($this->request->is('post')) {

			$this->loadModel('Tbl_company_accs');

			$reminderemail = $this->request->data['reminderemail'];

			$checkdata = array("email" => $reminderemail);

			$adminres = $this->Tbl_company_accs->find("all", array("conditions" => $checkdata, 'fields' => array('acc_password', 'email')), array('order' => array('id' => 'DESC')));

			$adminres->hydrate(false);

			$adminData = $adminres->toArray();

			if (!empty($adminData)) {

				$remEmail = $adminData['email'];

				$remPassword = base64_decode($adminData['acc_password']);

				$to = $reminderemail;

				$subject = "Password Reminder";

				$headers = "MIME-Version: 1.0\r\n";

				$headers .= 'From: info@lifeset.co.in' . "\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$body = "<html>

			<head>

			<title>Password Reminder</title>

			</head>

			<body>

			<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

			<tr>

			<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Login  :</h4></td>

			</tr>

			<tr>

			<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email Id  : $remEmail  </h3></td>

			</tr>

			<tr>

			<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : $remPassword  </h3></td>

			</tr>

			</table>

			</body>

			</html>";



				if ($response = mail($to, $subject, $body, $headers)) {

					$this->Flash->set('Password send in your email id');

					return $this->redirect(webURL . 'company-login');
					die;

				}

			} else {

				$this->Flash->set('Invalid email id');

				return $this->redirect(webURL . 'cmp-password-reminder');
				die;

			}

		}

	}

	public function studentNewRequest()
	{

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'status' => 0, 'new_request' => 1, 'college_id' => $collegeid)));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('viewData', $datast);



		if (isset($_GET['upid'])) {

			$upid = $_GET['upid'];

			$datacsLog = array('id' => $upid, 'status' => 1, 'new_request' => 0);

			$datacsSave = $this->Tbl_faculty_members->newEntity($datacsLog);

			$this->Tbl_faculty_members->save($datacsSave);



			$cmp = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $upid), 'fields' => array('mobile', 'acc_password', 'email')));

			$cmp->hydrate(false);

			$cmpdata = $cmp->first();



			$to = $cmpdata['email'];

			$subject = "Student Account Activated Successfully On LifeSet ";



			$headers = "MIME-Version: 1.0\r\n";

			$headers .= 'From: info@lifeset.co.in' . "\r\n";

			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$body = "<html>

					<head>

					<title>Student Account Activated Successfully On LifeSet</title>

					</head>

					<body>

					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

					<tr>

						<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Login Details  :</h4></td>

					</tr>

					<tr>

						<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:18px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Welcome to LifeSet. Your account has been successfully activated. Your Login ID " . webMobile . " and Password " . base64_decode($cmpdata['acc_password']) . ".  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Install our application by clicking on the logo or you can login in https://lifeset.co.in/login  </h3></td>

					</tr>

					<tr>

					<td style='padding-left: 55px; padding-right:55px;text-align: center;'><a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>

					<p>Students Community - LifeSet is a student’s community, which helps them for Internships, jobs, query solving and building network</p>

					</td>

					</tr>

					<tr>

					<td style='padding-left: 35px; padding-right:35px;'><p style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='left'>Regards,<br>

					Team LifeSet

					 </p></td>

					</tr>

					</table>

					</body>

					</html>";

			mail($to, $subject, $body, $headers);

			$this->Flash->success('Account successfully activated', array('key' => 'acc_alert', ));

			return $this->redirect(webURL . 'student-new-request');
			die;

		}

		if (isset($_GET['dlid'])) {



			$resmemdtlch = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $_GET['dlid']), 'fields' => array('id', 'college_id', 'mobile', 'email')));

			$resmemdtlch->hydrate(false);

			$datamemchdtl = $resmemdtlch->first();



			$contentmem = $this->Tbl_faculty_members->get($_GET['dlid']);

			if ($this->Tbl_faculty_members->delete($contentmem)) {



				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$resmemdtl = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $_GET['dlid']), 'fields' => 'id'));

				$resmemdtl->hydrate(false);

				$datamemdtl = $resmemdtl->first();

				if (!empty($datamemdtl)) {

					$contentmemdtl = $this->Tbl_member_details->get($datamemdtl['id']);

					$this->Tbl_member_details->delete($contentmemdtl);

				}

				/*	$respost =$this->Tbl_posts->find("all",array('conditions'=>array('college_id'=>$datamemchdtl['college_id']),'fields'=>'id'));	

																													 $respost->hydrate(false);

																													 $datapost =  $respost->toArray();

																													 if(!empty($datapost)){

																														 foreach($datapost as $dataposts){

																															 $contentpost = $this->Tbl_posts->get($dataposts['id']);

																															 $this->Tbl_posts->delete($contentpost);

																														 }

																													 }*/



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-new-request');
				die;

			}

		}

	}

	public function studentadminRequest()
	{

		$this->loadModel('Tbl_student_temp_registrations');

		$restd = $this->Tbl_student_temp_registrations->find("all", array('conditions' => array('status' => 0, 'new_college !=' => '')), array('order' => array('id' => 'DESC')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('viewData', $datast);



		if (isset($_GET['dlid'])) {

			$content = $this->Tbl_student_temp_registrations->get($_GET['dlid']);

			if ($this->Tbl_student_temp_registrations->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert', ));

				return $this->redirect(webURL . 'student-admin-list');
				die;

			}

		}

	}

	public function wallnotificationList()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_member_details');

		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$uploaded_date = date('Y-m-d h:i:s a');

		$condition = array();

		$last_date = date('Y-m-d h:i:s a', strtotime('-5 days'));

		if (!empty($stdId)) {



			$usersQuery1 = $this->Tbl_member_details->find('all', array('conditions' => array('acc_id' => $stdId), 'fields' => array('course', 'acc_id')));

			$usersQuery1->hydrate(false);

			$data1 = $usersQuery1->first();

			if ($profileType != 4) {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated > ' => $last_date, 'updated <=' => $uploaded_date);

			} else {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated > ' => $last_date, 'updated <=' => $uploaded_date);

			}

		} else if (!empty($cmpId)) {

			$condition = array('status' => 1, 'company_id' => $cmpId, 'updated > ' => $last_date, 'updated <=' => $uploaded_date);

		} else {

			$condition = array('status' => 1, 'updated > ' => $last_date, 'updated <=' => $uploaded_date);

		}



		if (isset($_GET['cat'])) {

			$this->loadModel('Wall_categorys');

			$cat = array();

			$respage = $this->Wall_categorys->find("all", array('conditions' => array('parent' => $_GET['cat'], 'status' => 1)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {
					$cat[] = $cmspages['id'];
				}

			}

			$cat[] = $_GET['cat'];

			$cat_search = array('category IN' => $cat);



			array_push($condition, $cat_search);

		}

		if (isset($_GET['type'])) {

			$type_search = array('post_type' => $_GET['type']);

			array_push($condition, $type_search);

		}

		$this->paginate = (array('limit' => 50, "conditions" => $condition, 'order' => array('updated' => 'desc')));

		$serser = $this->paginate('Tbl_posts');



		$this->set('blog', $serser);

	}

	public function postGridList()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_member_details');

		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$uploaded_date = date('Y-m-d h:i:s a');

		$condition = array();

		if (!empty($stdId)) {



			$usersQuery1 = $this->Tbl_member_details->find('all', array('conditions' => array('acc_id' => $stdId), 'fields' => array('course', 'acc_id')));

			$usersQuery1->hydrate(false);

			$data1 = $usersQuery1->first();



			if ($profileType != 4) {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated <=' => $uploaded_date);

			} else {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated <=' => $uploaded_date);

			}

		} else if (!empty($cmpId)) {

			$condition = array('status' => 1, 'company_id' => $cmpId, 'updated <=' => $uploaded_date);

		} else {

			$condition = array('status' => 1, 'updated <=' => $uploaded_date);

		}

		if (isset($_GET['cat'])) {

			$this->loadModel('Wall_categorys');

			$cat = array();

			$respage = $this->Wall_categorys->find("all", array('conditions' => array('parent' => $_GET['cat'], 'status' => 1)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {
					$cat[] = $cmspages['id'];
				}

			}

			$cat[] = $_GET['cat'];

			$cat_search = array('category IN' => $cat);

			array_push($condition, $cat_search);

		}

		if (isset($_GET['type'])) {

			$type_search = array('post_type' => $_GET['type']);

			array_push($condition, $type_search);

		}

		if (isset($_GET['key'])) {

			$key = $_GET['key'];

			$type_search = array('OR' => array('title LIKE' => '%' . $key . '%', 'function LIKE' => '%' . $key . '%', 'job_type LIKE' => '%' . $key . '%', 'industry LIKE' => '%' . $key . '%', 'role LIKE' => '%' . $key . '%', 'job_location LIKE' => '%' . $key . '%', 'skill LIKE' => '%' . $key . '%', 'capacity LIKE' => '%' . $key . '%', 'fixed_salary LIKE' => '%' . $key . '%', 'working_days LIKE' => '%' . $key . '%', 'work_time LIKE' => '%' . $key . '%', 'company_name LIKE' => '%' . $key . '%', 'past_experience LIKE' => '%' . $key . '%', 'stream LIKE' => '%' . $key . '%'));

			array_push($condition, $type_search);

		}

		$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

		$serser = $this->paginate('Tbl_posts');


		foreach ($serser as $post) {
			if ($post->post_type == 'GK') {
				$post->post_type = 'Current Affairs';
			} elseif ($post->post_type == "Job") {
				$post->post_type = "Fresher Jobs";
			} elseif ($post->post_type == "Internship") {
				$post->post_type = "Internships";
			} elseif ($post->post_type == "Exam") {
				$post->post_type = "Government Vacancies";
			} elseif ($post->post_type == "Event") {
				$post->post_type = "College Events";
			} elseif ($post->post_type == "Personality") {
				$post->post_type = "Know Yourself";
			}
		}

		$this->set('blog', $serser);

	}

	// public function postList()
	// {

	// 	$this->loadModel('Tbl_posts');

	// 	$respage = '';

	// 	$cmpid = $this->request->session()->read("company_accs.id");

	// 	$coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");

	// 	if (!empty($cmpid)) {



	// 		$respage = $this->Tbl_posts->find("all", array('conditions' => array('company_id' => $cmpid)), array('order' => array('updated' => 'desc')));

	// 	} else if (!empty($coll_id)) {

	// 		$respage = $this->Tbl_posts->find("all", array(
	// 			'conditions' => array(

	// 				'college_id' => $coll_id,

	// 				array('OR' => array('company_id' => 0, 'company_id' => ''), 'OR' => array('post_type !=' => 'Review', 'post_type !=' => 'Q&A'))

	// 			)
	// 		), array('order' => array('updated' => 'desc')));

	// 	} else {

	// 		$respage = $this->Tbl_posts->find("all", array('order' => array('updated' => 'desc')));

	// 	}

	// 	$respage->hydrate(false);

	// 	$cmspage = $respage->toArray();

	// 	$this->set('BlogPage', $cmspage);

	// }


	public function postList()
	{
		$this->loadModel('Tbl_posts');
		$cmpid = $this->request->session()->read("company_accs.id");
		$coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($cmpid)) {
			$respage = $this->Tbl_posts->find("all", [
				'conditions' => ['company_id' => $cmpid],
				'order' => ['updated' => 'desc']
			]);
		} elseif (!empty($coll_id)) {
			$respage = $this->Tbl_posts->find("all", [
				'conditions' => [
					'college_id' => $coll_id,
					'company_id' => 0,
					'AND' => [
						['post_type !=' => 'Review'],
						['post_type !=' => 'Q&A']
					]
				],
				'order' => ['updated' => 'desc']
			]);
		} else {
			$respage = $this->Tbl_posts->find("all", [
				'order' => ['updated' => 'desc']
			]);
		}

		$respage->hydrate(false);
		$cmspage = $respage->toArray();
		$this->set('BlogPage', $cmspage);
	}



	public function postList5()
	{
		$this->loadModel('PostGk'); // table load karo

		// Badha GK Posts fetch karva
		$respage = $this->PostGk->find('all', [
			'order' => ['updated' => 'desc'] // latest updated pehla aavse
		]);

		// Array ma convert karva
		$respage->enableHydration(false); // CakePHP 4+
		$cmspage = $respage->toArray();



		echo "<pre>";
		print_r($cmspage);
		echo "</pre>";
		exit;

		// view ma send karo
		$this->set('GkPosts', $cmspage);
	}


	public function postgkList()
	{
		$this->loadModel('Post_main');

		$cmpid = $this->request->session()->read("company_accs.id");
		$coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($cmpid)) {
			$respage = $this->Post_main->find("all", [
				'conditions' => ['company_id' => $cmpid],
				'order' => ['updated' => 'desc']
			]);
		} elseif (!empty($coll_id)) {
			$respage = $this->Post_main->find("all", [
				'conditions' => [
					'college_id' => $coll_id,
					'company_id' => 0,
					'AND' => [
						['post_type !=' => 'Review'],
						['post_type !=' => 'Q&A']
					]
				],
				'order' => ['updated' => 'desc']
			]);
		} else {
			$respage = $this->Post_main->find("all", [
				'order' => ['updated' => 'desc']
			]);
		}

		$respage->hydrate(false);
		$cmspage = $respage->toArray();

		$this->set('BlogPage', $cmspage);
	}

	// public function postList()
// {
//     $this->loadModel('Tbl_posts');
//     $cmpid = $this->request->session()->read("company_accs.id");
//     $coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");

	//     if (!empty($cmpid)) {
//         $respage = $this->Tbl_posts->find("all", [
//             'conditions' => ['company_id' => $cmpid],
//             'order' => ['updated' => 'desc']
//         ]);
//     } elseif (!empty($coll_id)) {
//         $respage = $this->Tbl_posts->find("all", [
//             'conditions' => [
//                 'college_id' => $coll_id,
//                 'company_id' => 0,
//                 'AND' => [
//                     ['post_type !=' => 'Review'],
//                     ['post_type !=' => 'Q&A']
//                 ]
//             ],
//             'order' => ['updated' => 'desc']
//         ]);
//     } else {
//         // All posts if no specific session values found
//         $respage = $this->Tbl_posts->find("all", [
//             'order' => ['updated' => 'desc']
//         ]);
//     }

	//     $respage->hydrate(false);
//     $cmspage = $respage->toArray();

	//     $this->set('BlogPage', $cmspage);
// }

	public function wallpostingList()
	{

		$this->loadModel('Tbl_posts');

		$respage = '';

		$type = 'Job';

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => $type), 'order' => array('updated' => 'desc')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('BlogPage', $cmspage);

	}

	public function addeditPost1()
	{

		$this->loadModel('Tbl_posts');

		$cmpid = $this->request->session()->read("company_accs.id");

		$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		if (!empty($cmpid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else if (!empty($collid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $collid, 'id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		}

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {

			if (!isset($this->request->data['college_id'])) {

				$this->request->data['college_id'] = $collid;

			}

			if (!empty($cmpid)) {
				$this->request->data['company_id'] = $cmpid;
			}



			$savedata = array();

			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['created'] = date('Y-m-d h:i:s a');

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			}



			if ($this->request->data['post_type'] == 'Review') {



				$pid = $this->request->data['id'];

				$post_type = $this->request->data['post_type'];

				$question = $this->request->data['question'];

				$options = $this->request->data['options'];



				if (!empty($this->request->data['postimage']['name'])) {

					$clg_logo = $file = $this->request->data['postimage'];

					$clg_logo_name = $file = $this->request->data['postimage']['name'];

					$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

					$save_clglogo = time() . $clg_logo_name;



					//$save_file=$this->uploadImage($save_clglogo,$clg_logo_path,'img/Post/','650','250');

					$save_file = $save_clglogo;

					@move_uploaded_file($file, "img/Post/" . $save_file);

					$this->request->data['image'] = $save_file;

					if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

						unlink('img/Post/' . $this->request->data['saveimage']);

					}

					$this->request->data['image'] = $save_file;

				}

				if (isset($options)) {

					$this->request->data['options'] = implode(';;', $options);

					foreach ($options as $optionss) {

						$rating[] = 5;

					}

					$this->request->data['rating'] = implode(';;', $rating);

				}

				unset($this->request->data['client_to_manage']);

				unset($this->request->data['capacity']);

				unset($this->request->data['working_days']);

				unset($this->request->data['fixed_salary']);

				unset($this->request->data['variable_sallery']);

				unset($this->request->data['postimage']);

				unset($this->request->data['function']);

				unset($this->request->data['saveimage']);

				unset($this->request->data['title']);

				unset($this->request->data['post_url']);

				unset($this->request->data['pincode']);

				unset($this->request->data['state']);

				unset($this->request->data['hobbies']);

				unset($this->request->data['district']);

				unset($this->request->data['description']);

				unset($this->request->data['past_experience']);

				unset($this->request->data['industry']);

				unset($this->request->data['role']);

				unset($this->request->data['job_location']);

				unset($this->request->data['skill']);

				unset($this->request->data['job_type']);

				unset($this->request->data['work_time']);

				unset($this->request->data['description1']);

				unset($this->request->data['objquestion']);

				unset($this->request->data['answer']);

				unset($this->request->data['right_answer']);



			} else if ($this->request->data['post_type'] == 'Q&A') {



				$objquestion = $this->request->data['objquestion'];

				$answer = $this->request->data['answer'];

				$right_answer = $this->request->data['right_answer'];



				if (isset($answer)) {

					$this->request->data['answer'] = implode(';;', $answer);

				}

				if (isset($right_answer)) {

					$this->request->data['right_answer'] = implode(';;', $right_answer);

				}

				unset($this->request->data['client_to_manage']);

				unset($this->request->data['capacity']);

				unset($this->request->data['working_days']);

				unset($this->request->data['fixed_salary']);

				unset($this->request->data['variable_sallery']);

				unset($this->request->data['postimage']);

				unset($this->request->data['question']);

				unset($this->request->data['function']);

				unset($this->request->data['saveimage']);

				unset($this->request->data['option']);

				unset($this->request->data['rating']);

				unset($this->request->data['title']);

				unset($this->request->data['post_url']);

				unset($this->request->data['pincode']);

				unset($this->request->data['state']);

				unset($this->request->data['hobbies']);

				unset($this->request->data['district']);

				unset($this->request->data['description']);

				unset($this->request->data['past_experience']);

				unset($this->request->data['industry']);

				unset($this->request->data['role']);

				unset($this->request->data['job_location']);

				unset($this->request->data['skill']);

				unset($this->request->data['job_type']);

				unset($this->request->data['work_time']);

				unset($this->request->data['description1']);



			} else if ($this->request->data['post_type'] == 'Job' || $this->request->data['post_type'] == 'Internship') {



				unset($this->request->data['title']);

				unset($this->request->data['post_url']);

				unset($this->request->data['pincode']);

				unset($this->request->data['state']);

				unset($this->request->data['district']);

				unset($this->request->data['description']);

				$this->request->data['description'] = $this->request->data['description1'];

				$this->request->data['title'] = $this->request->data['title1'];



				if (!empty($this->request->data['postimage']['name'])) {

					$clg_logo = $file = $this->request->data['postimage'];

					$clg_logo_name = $file = $this->request->data['postimage']['name'];

					$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

					$save_clglogo = time() . $clg_logo_name;



					//$save_file=$this->uploadImage($save_clglogo,$clg_logo_path,'img/Post/','650','250');

					$save_file = $save_clglogo;

					@move_uploaded_file($file, "img/Post/" . $save_file);

					$this->request->data['image'] = $save_file;

					if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

						unlink('img/Post/' . $this->request->data['saveimage']);

					}

					//@move_uploaded_file($file,"img/Post/".$save_file);

					$this->request->data['image'] = $save_file;

				}

			} else {

				unset($this->request->data['status']);

				$this->request->data['status'] = $this->request->data['status1'];

				unset($this->request->data['industry']);

				unset($this->request->data['function']);

				unset($this->request->data['role']);

				unset($this->request->data['past_experience']);

				unset($this->request->data['job_location']);

				unset($this->request->data['skill']);

				unset($this->request->data['job_type']);

				unset($this->request->data['client_to_manage']);

				unset($this->request->data['capacity']);

				unset($this->request->data['salary']);

				unset($this->request->data['cutoff']);

				unset($this->request->data['working_days']);

				unset($this->request->data['work_time']);

			}

			unset($this->request->data['postimage']);

			unset($this->request->data['saveimage']);



			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'post-list');
				die;

			}

		}

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'post-list');
					die;

				}

			}

		}

	}

	public function get_postid($id = 0)
	{

		if (!empty($id)) {

			$ckcondion = array('id' => $id);

			$reslast = $this->Tbl_posts->find("all", array('conditions' => $ckcondion, 'order' => array('id' => 'desc'), 'fields' => array('id')));

		} else {

			$reslast = $this->Tbl_posts->find("all", array('order' => array('id' => 'desc'), 'fields' => array('id')));

		}

		$reslast->hydrate(false);

		$datalast = $reslast->first();

		return $datalast['id'];

	}

	/*public function get_post_seos($id=0)

							{   

								$this->loadModel('Tbl_post_seos'); 

								$datalast='';

								 if(!empty($id)){

										 $ckcondion=array('post_id'=>$id);  

										 $reslast =$this->Tbl_post_seos->find("all",array('conditions'=>$ckcondion,'fields'=>array('seo_title','seo_keyword','seo_description')));

										$reslast->hydrate(false);

										$datalast=  $reslast->first();

										}

									return 	$datalast;			

							}*/

	public function check_remain_post_credit_scores($cmpid = '')
	{

		$this->loadModel('Recruiter_credit_posts');

		$this->loadModel('Recruiter_credit_deductions');

		$totalremaincreadit = 0;

		//print_r($cmpid);die;

		$restdpoint = $this->Recruiter_credit_posts->find("all", array('conditions' => array('cmp_id' => $cmpid), 'fields' => array('total_posts')));

		$restdpoint->hydrate(false);

		$datapoint = $restdpoint->toArray();

		if (!empty($datapoint)) {

			$ttlpoints = array();

			foreach ($datapoint as $datapoints) {

				$ttlpoints[] = $datapoints['total_posts'];

			}

			$totalcreadit = array_sum($ttlpoints);



			$usersQuery = $this->Recruiter_credit_deductions->find('all', array('conditions' => array('cmp_id' => $cmpid)));

			$usersQuery->hydrate(false);

			$datashortpoint = $usersQuery->toArray();

			$totalshortcreadit = 0;

			if (!empty($datashortpoint)) {

				$ttlshortpoints = array();

				foreach ($datashortpoint as $datashortpoints) {
					$ttlshortpoints[] = $datashortpoints['credit'];
				}

				$totalshortcreadit = array_sum($ttlshortpoints);

			}

			$creaditremove = $totalshortcreadit;

			$totalremaincreadit = $totalcreadit - $creaditremove;

		}

		return $totalremaincreadit;
		die;

	}


	public function personalityReport()
	{
		$this->loadModel('Tbl_personality_posts');
		$this->loadModel('Tbl_posts');

		$query = $this->Tbl_personality_posts->find('all', [
			'contain' => ['Tbl_posts'], // If you have association
			'order' => ['Tbl_personality_posts.id' => 'DESC']
		]);

		$personalityPosts = $query->toArray();

		$this->set(compact('personalityPosts'));
	}


	// public function addeditgkPost($postData)
	// {


	// 	print_r("running");
	// 	exit;
	// 	$this->loadModel('Tbl_posts');
	// 	$this->loadModel('Tbl_post_seos');
	// 	$this->loadModel('Tbl_gk_posts');
	// 	$this->loadModel('Recruiter_credit_deductions');
	// 	$this->loadModel('Tbl_faculty_members');

	// 	$cmpid = $this->request->session()->read("company_accs.id");
	// 	$memberid = $this->request->session()->read("Tbl_faculty_members.id");
	// 	$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");

	// 	if (!empty($collid)) {
	// 		$postData['college_id'] = $collid;
	// 		$postData['post_by'] = $memberid;
	// 	} else if (!empty($cmpid)) {
	// 		$postData['company_id'] = $cmpid;
	// 		$postData['post_by'] = $cmpid;
	// 		$postData['college_id'] = '';
	// 	} else {
	// 		$postData['college_id'] = '';
	// 		$postData['company_id'] = '';
	// 	}

	// 	// Slug generate karvu
	// 	$postData['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $postData['title'])));

	// 	if (!empty($postData['id'])) {
	// 		$postData['updated'] = date('Y-m-d h:i:s a');
	// 	} else {
	// 		$postData['created'] = date('Y-m-d h:i:s a');
	// 		$postData['updated'] = date('Y-m-d h:i:s a');
	// 	}

	// 	// Status for preview or publish
	// 	if (isset($postData['Preview'])) {
	// 		$postData['status'] = 0;
	// 	} else {
	// 		$postData['status'] = 1;
	// 	}

	// 	// Save Tbl_posts
	// 	$dataToSave = $this->Tbl_posts->newEntity($postData);
	// 	if ($this->Tbl_posts->save($dataToSave)) {
	// 		$postid = $this->get_postid($postData['id']);

	// 		// Credit deduction if new post
	// 		if (empty($postData['id']) && !empty($cmpid)) {
	// 			$resded['cmp_id'] = $cmpid;
	// 			$resded['post_id'] = $postid;
	// 			$resded['credit'] = 1;
	// 			$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);
	// 			$this->Recruiter_credit_deductions->save($datadedct);
	// 		}

	// 		// Save SEO data
	// 		$saveseopost = [
	// 			'id' => !empty($postData['seo_id']) ? $postData['seo_id'] : null,
	// 			'post_id' => $postid,
	// 			'seo_title' => $postData['seo_title'] ?? '',
	// 			'seo_keyword' => $postData['seo_keyword'] ?? '',
	// 			'seo_description' => $postData['seo_description'] ?? '',
	// 		];
	// 		$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);
	// 		$this->Tbl_post_seos->save($postToSaveseo);

	// 		// Save GK post details
	// 		$savepost = [
	// 			'id' => $postData['gkid'] ?? null,
	// 			'post_id' => $postid,
	// 			'cat_id' => $postData['survey_cat_id'] ?? null,
	// 			'title' => $postData['gk_title'] ?? '',
	// 			'post_url' => $postData['gk_post_url'] ?? '',
	// 			'pincode' => $postData['gk_pincode'] ?? '',
	// 			'state' => $postData['gk_state'] ?? '',
	// 			'district' => $postData['gk_district'] ?? '',
	// 			'hobbies' => $postData['gk_hobbies'] ?? '',
	// 			'description' => $postData['gk_description'] ?? '',
	// 		];

	// 		// Upload image if exists
	// 		if (!empty($postData['postimage']['name'])) {
	// 			$clg_logo = $postData['postimage'];
	// 			$clg_logo_name = $clg_logo['name'];
	// 			$clg_logo_path = $clg_logo['tmp_name'];
	// 			$save_clglogo = time() . $clg_logo_name;
	// 			$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

	// 			if (!empty($postData['saveimage']) && file_exists('img/Post/' . $postData['saveimage'])) {
	// 				unlink('img/Post/' . $postData['saveimage']);
	// 			}

	// 			$savepost['image'] = $save_file;
	// 		}

	// 		$postToSave = $this->Tbl_gk_posts->newEntity($savepost);
	// 		$this->Tbl_gk_posts->save($postToSave);

	// 		// Send notification if published
	// 		if ($postData['status'] == 1) {
	// 			$facultyTokens = $this->Tbl_faculty_members->find('list', [
	// 				'keyField' => 'id',
	// 				'valueField' => 'fcm_token',
	// 				'conditions' => ['fcm_token IS NOT' => null]
	// 			])->toArray();

	// 			$title = "LifeSet | GK & CA ";
	// 			$body = $postData['gk_title'] ?? '';
	// 			$this->sendNotificationsToFcmTokens($facultyTokens, $title, $body);
	// 		}

	// 		$this->Flash->success('GK Data successfully saved', ['key' => 'acc_alert']);

	// 		if (isset($postData['Preview'])) {
	// 			$pageid = !empty($postData['id']) ? $postData['id'] : $postid;
	// 			return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageid);
	// 		} else {
	// 			return $this->redirect(webURL . 'post-list');
	// 		}
	// 	}
	// }
	public function addeditgkPost123($id = null)
	{
		$this->loadModel('Tbl_gk_posts');
		$this->loadModel('Tbl_categories');
		$this->loadModel('Tbl_subcategories');
		$this->loadModel('Tbl_sections');

		$gkPost = $id
			? $this->Tbl_gk_posts->find()->where(['id' => $id])->first()
			: $this->Tbl_gk_posts->newEntity();



		$categories = $this->Tbl_categories->find('list')->toArray();
		$subcategories = $this->Tbl_subcategories->find('list')->toArray();
		$sections = $this->Tbl_sections->find('list')->toArray();

		$this->set(compact('gkPost', 'categories', 'subcategories', 'sections'));
	}


	public function addeditPost()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_post_seos');

		$this->loadModel('Tbl_exam_posts');

		$this->loadModel('Tbl_personality_posts');

		$this->loadModel('Tbl_survey_posts');

		$this->loadModel('Tbl_gk_posts');



		$this->loadModel('Recruiter_credit_posts');

		$this->loadModel('Recruiter_credit_deductions');



		$cmpid = $this->request->session()->read("company_accs.id");

		$memberid = $this->request->session()->read("Tbl_faculty_members.id");

		$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$profileType = $this->request->session()->read("Admincheck.admin-logtype");

		if (isset($_GET['pageid']) && !empty($_GET['pageid'])) {



			$pageid = $_GET['pageid'];



		} else {



			if (!in_array($profileType, array(1, 2))) {

				$remain_posts = $this->check_remain_post_credit_scores($cmpid);

				if ($remain_posts <= 0) {

					$this->Flash->success('You do not have enough credit to a add new post', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'post-list');
					die;

				}

			}

			$pageid = '';





		}

		if (isset($_GET['type'])) {

			if (!in_array($profileType, array(1, 2))) {

				$type = $_GET['type'];

				$remain_posts = $this->check_remain_post_credit_scores($cmpid);

				if ($remain_posts <= 0 && $type == 'copy') {

					$this->Flash->success('You have no enough credit for add new post', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'post-list');
					die;

				}

			}



		} else {
			$type = '';
		}



		if (!empty($cmpid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else if (!empty($collid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $collid, 'id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		}

		$this->set('viewData', $cmspage);

		//print_r($cmspage);die;



		if ($this->request->is('post')) {



			if (isset($this->request->data['Deactive'])) {

				//print_r('Deactive error');die;

				$this->request->data['status'] = 0;

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					$this->Flash->success('Data successfully updated', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'post-list');
					die;

				}

			}

			if (!empty($collid)) {

				$this->request->data['college_id'] = $collid;

				$this->request->data['post_by'] = $memberid;

			} else if (!empty($cmpid)) {

				$this->request->data['company_id'] = $cmpid;

				$this->request->data['post_by'] = $cmpid;

				$this->request->data['college_id'] = '';

			} else {

				$this->request->data['college_id'] = '';

				$this->request->data['company_id'] = '';

			}

			$savedata = array();

			$savepost = array();



			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['created'] = date('Y-m-d h:i:s a');

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			}

			/* 

																					if(isset($this->request->data['exam_cat_id']) && $this->request->data['exam_cat_id']!='Other'){ 

																					  $this->request->data['category']=$this->request->data['exam_cat_id'];

																					}else if(isset($this->request->data['personality_cat_id']) && $this->request->data['personality_cat_id']!='Other'){ 

																					  $this->request->data['category']=$this->request->data['personality_cat_id'];

																					}else if(isset($this->request->data['survey_cat_id']) && $this->request->data['survey_cat_id']!='Other'){ 

																					  $this->request->data['category']=$this->request->data['survey_cat_id'];

																					}*/



			$seo_title = $this->request->data['seo_title'];

			$seo_keyword = $this->request->data['seo_keyword'];

			$seo_description = $this->request->data['seo_description'];

			//print_r($this->request->data['category']);die;

			if ($this->request->data['post_type'] == 'Event') {

				// Handle status (Publish or Preview)
				if (isset($this->request->data['Preview'])) {
					unset($this->request->data['status']);
					$this->request->data['status'] = 0;
				} else {
					$this->request->data['status'] = 1;
				}

				// Image upload
				if (!empty($this->request->data['postimage']['name'])) {
					$clg_logo = $this->request->data['postimage'];
					$clg_logo_name = $clg_logo['name'];
					$clg_logo_path = $clg_logo['tmp_name'];
					$save_clglogo = time() . $clg_logo_name;

					$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');
					$this->request->data['image'] = $save_file;

					if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {
						unlink('img/Post/' . $this->request->data['saveimage']);
					}
				}

				// Save Event data
				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);
				if ($this->Tbl_posts->save($dataToSave)) {
					$postid = $this->get_postid($this->request->data['id']);

					// Send notification if published
					if ($this->request->data['status'] == 1) {
						$facultyTokens = $this->Tbl_faculty_members->find('list', [
							'keyField' => 'id',
							'valueField' => 'fcm_token',
							'conditions' => ['fcm_token IS NOT' => null]
						])->toArray();

						$this->sendNotificationsToFcmTokens(
							$facultyTokens,
							"LifeSet | Event",
							$this->request->data['title'] ?? 'New Event Posted'
						);
					}

					$this->Flash->success('Event successfully saved', ['key' => 'acc_alert']);

					// Redirect
					if (isset($this->request->data['Preview'])) {
						$pageids = !empty($this->request->data['id']) ? $this->request->data['id'] : $postid;
						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids);
					} else {
						return $this->redirect(webURL . 'post-list');
					}
				}
			}

			if ($this->request->data['post_type'] == 'Exam') {

				$pid = $this->request->data['id'];

				if (isset($this->request->data['Preview'])) {
					unset($this->request->data['status']);
					$this->request->data['status'] = 0;
				} else {
					$this->request->data['status'] = 1;
				}

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);
				if ($this->Tbl_posts->save($dataToSave)) {

					$postid = $this->get_postid($this->request->data['id']);

					// Save SEO
					$saveseopost = [];
					if (!empty($this->request->data['seo_id'])) {
						$saveseopost['id'] = $this->request->data['seo_id'];
					}

					$saveseopost['post_id'] = $postid;
					$saveseopost['seo_title'] = $seo_title;
					$saveseopost['seo_keyword'] = $seo_keyword;
					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);
					$this->Tbl_post_seos->save($postToSaveseo);

					// Save Exam Post
					$savepost = [];
					$savepost['id'] = $this->request->data['examid'];
					$savepost['post_id'] = $postid;
					$savepost['cat_id'] = $this->request->data['exam_cat_id'];

					if ($savepost['cat_id'] == 'Other') {
						$savepost['other_exam_category'] = $this->request->data['other_exam_category'];
						$this->request->data['category'] = '';
					} else {
						$savepost['other_exam_category'] = '';
						$this->request->data['category'] = $this->request->data['exam_cat_id'];
					}

					$savepost['exam_name'] = $this->request->data['exam_name'];
					$savepost['name_of_post'] = $this->request->data['name_of_post'];
					$savepost['exam_level'] = $this->request->data['exam_level'];
					$savepost['other_exam_level'] = $this->request->data['exam_level'] == 'Other' ? $this->request->data['other_exam_level'] : '';
					$savepost['announcement_date'] = $this->request->data['announcement_date'];
					$savepost['last_date_form_filling'] = $this->request->data['last_date_form_filling'];
					$savepost['admit_card'] = $this->request->data['admit_card'];
					$savepost['exam_date'] = $this->request->data['exam_date'];
					$savepost['exam_time'] = $this->request->data['exam_time'];
					$savepost['result'] = $this->request->data['result'];
					$savepost['fees'] = $this->request->data['fees'];
					$savepost['vacancy'] = $this->request->data['vacancy'];
					$savepost['exam_pattern'] = $this->request->data['exam_pattern'];
					$savepost['cutoff'] = $this->request->data['cutoff'];
					$savepost['eligibility'] = $this->request->data['eligibility'];
					$savepost['age_limit'] = $this->request->data['age_limit'];
					$savepost['description'] = $this->request->data['exam_description'];

					// Image Upload
					if (!empty($this->request->data['postimage']['name'])) {
						$clg_logo_name = $this->request->data['postimage']['name'];
						$clg_logo_path = $this->request->data['postimage']['tmp_name'];
						$save_clglogo = time() . $clg_logo_name;

						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');
						$this->request->data['image'] = $save_file;

						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {
							unlink('img/Post/' . $this->request->data['saveimage']);
						}

						$savepost['image'] = $save_file;
					}

					// Save final Tbl_posts again with image if uploaded
					if (!empty($postid)) {
						$this->request->data['id'] = $postid;
					}

					$dataToSave = $this->Tbl_posts->newEntity($this->request->data);
					$this->Tbl_posts->save($dataToSave);

					$postToSave = $this->Tbl_exam_posts->newEntity($savepost);
					$POSTSave = $this->Tbl_exam_posts->save($postToSave);

					if (empty($this->request->data['id'])) {
						$resded['cmp_id'] = $cmpid;
						$resded['post_id'] = $POSTSave->id;
						$resded['credit'] = 1;

						$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);

						$this->Recruiter_credit_deductions->save($datadedct);
					}
					if ($this->request->data['status'] == 1) {
						$currentTime = date('h:i A'); // Example: 10:23 PM

						$facultyTokens = $this->Tbl_faculty_members->find('list', [
							'keyField' => 'id',
							'valueField' => 'fcm_token',
							'conditions' => ['fcm_token IS NOT' => null]
						])->toArray();

						// Call notification function with title and body
						$this->sendNotificationsToFcmTokens(
							$facultyTokens,
							"LifeSet | Govt Exams ", // ✅ dynamic title with time
							$this->request->data['exam_name'] // ✅ dynamic body
						);
					}


					$this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);

					// Redirect for Preview or List
					if (isset($this->request->data['Preview'])) {
						if (!empty($this->request->data['id'])) {
							$pageids = $this->request->data['id'];
						} else {
							$respage2 = $this->Tbl_posts->find("all", ['order' => ['id' => 'desc'], 'fields' => ['id']]);
							$respage2->hydrate(false);
							$cmspage3 = $respage2->first();
							$pageids = $cmspage3['id'];
						}

						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids);
					} else {
						return $this->redirect(webURL . 'post-list');
					}
				}
			} else if ($this->request->data['post_type'] == 'Personality') {

				$pid = $this->request->data['id'];

				// Handle Preview Status
				$this->request->data['status'] = isset($this->request->data['Preview']) ? 0 : 1;

				// Save general post data
				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					// Get post id
					$postid = $this->get_postid($this->request->data['id']);

					// Deduct recruiter credit if it's a new post
					if (empty($this->request->data['id'])) {
						$resded = [
							'cmp_id' => $cmpid,
							'post_id' => $postid,
							'credit' => 1
						];
						$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);
						// $this->Recruiter_credit_deductions->save($datadedct); // Uncomment if needed
					}

					// Save SEO data
					$saveseopost = [
						'post_id' => $postid,
						'seo_title' => $seo_title,
						'seo_keyword' => $seo_keyword,
						'seo_description' => $seo_description
					];
					if (!empty($this->request->data['seo_id'])) {
						$saveseopost['id'] = $this->request->data['seo_id'];
					}

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);
					$this->Tbl_post_seos->save($postToSaveseo);

					// Prepare personality post data
					$savepost = [
						'id' => $this->request->data['personalityid'],
						'post_id' => $postid,
						'cat_id' => $this->request->data['personality_cat_id'],
						'name' => $this->request->data['personality_question'],
						'answer' => $this->request->data['personality_answer'],
						'yesis' => $this->request->data['personality_yesis'],
						'nois' => $this->request->data['personality_nois'],
					];

					// Assign post category
					$this->request->data['category'] = $this->request->data['personality_cat_id'];
					unset($this->request->data['personality_cat_id']);

					// Handle image upload
					if (!empty($this->request->data['postimage']['name'])) {
						$clg_logo_name = $this->request->data['postimage']['name'];
						$clg_logo_path = $this->request->data['postimage']['tmp_name'];
						$save_clglogo = time() . $clg_logo_name;

						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						// Delete old image if exists
						if (!empty($this->request->data['saveimage']) && file_exists('img/Post/' . $this->request->data['saveimage'])) {
							unlink('img/Post/' . $this->request->data['saveimage']);
						}

						$savepost['image'] = $save_file;
					}

					// ✅ Update or Insert personality post
					if (!empty($this->request->data['personalityid'])) {
						// Update case
						$existingPost = $this->Tbl_personality_posts->get($this->request->data['personalityid']);
						$postToSave = $this->Tbl_personality_posts->patchEntity($existingPost, $savepost);
					} else {
						// Insert case
						$postToSave = $this->Tbl_personality_posts->newEntity($savepost);
					}

					// Debug errors if any
					if ($postToSave->getErrors()) {
						echo "<pre>";
						print_r($postToSave->getErrors());
						echo "</pre>";
					}

					if ($this->Tbl_personality_posts->save($postToSave)) {
						$this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);
					} else {
						$this->Flash->error('Failed to save personality post', ['key' => 'acc_alert']);
					}

					// Redirect
					if (isset($this->request->data['Preview'])) {
						$pageids = $this->get_postid($this->request->data['id']);
						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids);
					} else {
						return $this->redirect(webURL . 'post-list');
					}
				}
			} else if ($this->request->data['post_type'] == 'Survey') {

				$pid = $this->request->data['id'];



				if (isset($this->request->data['Preview'])) {

					unset($this->request->data['status']);

					$this->request->data['status'] = 0;

				} else {

					$this->request->data['status'] = 1;

				}

				//if($type!="copy"){  unset($this->request->data['id']); }

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					$postid = $this->get_postid($this->request->data['id']);



					if (empty($this->request->data['id'])) {

						//Recruiter credit deductions......................	



						$resded['cmp_id'] = $cmpid;

						$resded['post_id'] = $postid;

						$resded['credit'] = 1;

						$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);

						$this->Recruiter_credit_deductions->save($datadedct);

					}

					$saveseopost = array();

					//if($type!="copy"){ 

					if (!empty($this->request->data['seo_id'])) {

						$saveseopost['id'] = $this->request->data['seo_id'];

					}

					// }

					$saveseopost['post_id'] = $postid;

					$saveseopost['seo_title'] = $seo_title;

					$saveseopost['seo_keyword'] = $seo_keyword;

					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);

					$this->Tbl_post_seos->save($postToSaveseo);



					$savepost['id'] = $this->request->data['surveyid'];

					$savepost['post_id'] = $postid;

					$savepost['cat_id'] = $this->request->data['survey_cat_id'];

					$savepost['question'] = $this->request->data['survey_question'];

					$answer = $this->request->data['survey_answer'];

					$right_answer = $this->request->data['survey_right_answer'];



					$this->request->data['category'] = $this->request->data['survey_cat_id'];

					unset($this->request->data['survey_cat_id']);



					if (isset($answer)) {

						$savepost['answer'] = implode(';;', $answer);

					}

					if (isset($right_answer)) {

						$savepost['right_answer'] = implode(';;', $right_answer);

					}

					//$this->request->data['category']='';

					if (!empty($this->request->data['postimage']['name'])) {

						$clg_logo = $file = $this->request->data['postimage'];

						$clg_logo_name = $file = $this->request->data['postimage']['name'];

						$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

						$save_clglogo = time() . $clg_logo_name;

						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

							unlink('img/Post/' . $this->request->data['saveimage']);

						}

						$savepost['image'] = $save_file;

					}



					$postToSave = $this->Tbl_survey_posts->newEntity($savepost);

					$this->Tbl_survey_posts->save($postToSave);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));



					if (isset($this->request->data['Preview'])) {

						if (!empty($this->request->data['id'])) {

							$pageids = $this->request->data['id'];

						} else {

							$pageids = $this->get_postid($this->request->data['id']);

							//$pageids=$cmspage3['id'];



						}

						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids);
						die;

					} else {

						return $this->redirect(webURL . 'post-list');
						die;

					}

				}

			} else if ($this->request->data['post_type'] == 'GK') {

				$pid = $this->request->data['id'];

				if (isset($this->request->data['Preview'])) {
					unset($this->request->data['status']);
					$this->request->data['status'] = 0;
				} else {
					$this->request->data['status'] = 1;
				}

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);
				if ($this->Tbl_posts->save($dataToSave)) {

					$postid1 = $this->get_postid($this->request->data['id']);

					// Save final Tbl_posts again with image if uploaded
					if (!empty($postid1)) {
						$this->request->data['id'] = $postid1;
					}

					if (empty($this->request->data['id'])) {
						$resded['cmp_id'] = $cmpid;
						$resded['post_id'] = $postid1;
						$resded['credit'] = 1;
						$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);
						$this->Recruiter_credit_deductions->save($datadedct);
					}
					$saveseopost = array();
					if (!empty($this->request->data['seo_id'])) {
						$saveseopost['id'] = $this->request->data['seo_id'];
					}
					$saveseopost['post_id'] = $postid1;
					$saveseopost['seo_title'] = $seo_title;
					$saveseopost['seo_keyword'] = $seo_keyword;
					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);
					$this->Tbl_post_seos->save($postToSaveseo);

					$savepost['id'] = $this->request->data['gkid'];
					$savepost['post_id'] = $postid1;
					$savepost['cat_id'] = $this->request->data['survey_cat_id'];
					$savepost['title'] = $this->request->data['gk_title'];
					$savepost['post_url'] = $this->request->data['gk_post_url'];
					$savepost['pincode'] = $this->request->data['gk_pincode'];
					$savepost['state'] = $this->request->data['gk_state'];
					$savepost['district'] = $this->request->data['gk_district'];
					$savepost['hobbies'] = $this->request->data['gk_hobbies'];
					$savepost['description'] = $this->request->data['gk_description'];

					if (!empty($this->request->data['postimage']['name'])) {
						$clg_logo = $this->request->data['postimage'];
						$clg_logo_name = $this->request->data['postimage']['name'];
						$clg_logo_path = $this->request->data['postimage']['tmp_name'];
						$save_clglogo = time() . $clg_logo_name;
						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {
							unlink('img/Post/' . $this->request->data['saveimage']);
						}

						$savepost['image'] = $save_file;
					}

					$postToSave = $this->Tbl_gk_posts->newEntity($savepost);
					$this->Tbl_gk_posts->save($postToSave);
					// ✅ Send notifications ONLY if published (status == 1)
					if ($this->request->data['status'] == 1) {
						$currentTime = date('h:i A'); // Example: 10:32 PM

						$facultyTokens = $this->Tbl_faculty_members->find('list', [
							'keyField' => 'id',
							'valueField' => 'fcm_token',
							'conditions' => ['fcm_token IS NOT' => null]
						])->toArray();

						$title = "LifeSet | GK & CA ";
						$body = $this->request->data['gk_title'];

						$this->sendNotificationsToFcmTokens($facultyTokens, $title, $body);
					}

					$this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);

					if (isset($this->request->data['Preview'])) {
						if (!empty($this->request->data['id'])) {
							$pageids1 = $this->request->data['id'];
						} else {
							$pageids1 = $this->get_postid($this->request->data['id']);
						}

						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids1);
					} else {
						return $this->redirect(webURL . 'post-list');
					}
				}

				$this->request->data['category'] = '';
			} else {



				if ($this->request->data['post_type'] == 'Review') {



					$pid = $this->request->data['id'];

					$post_type = $this->request->data['post_type'];

					$question = $this->request->data['question'];

					$options = $this->request->data['options'];



					if (!empty($this->request->data['postimage']['name'])) {

						$clg_logo = $file = $this->request->data['postimage'];

						$clg_logo_name = $file = $this->request->data['postimage']['name'];

						$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

						$save_clglogo = time() . $clg_logo_name;



						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						//@move_uploaded_file($file,"img/Post/".$save_file);

						$this->request->data['image'] = $save_file;

						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

							unlink('img/Post/' . $this->request->data['saveimage']);

						}

						$this->request->data['image'] = $save_file;

					}

					if (isset($options)) {

						$this->request->data['options'] = implode(';;', $options);

						foreach ($options as $optionss) {

							$rating[] = 5;

						}

						$this->request->data['rating'] = implode(';;', $rating);

					}



					$this->request->data['category'] = $this->request->data['review_category'];

					unset($this->request->data['review_category']);



					unset($this->request->data['client_to_manage']);

					unset($this->request->data['capacity']);

					unset($this->request->data['working_days']);

					unset($this->request->data['fixed_salary']);

					unset($this->request->data['variable_sallery']);

					unset($this->request->data['postimage']);

					unset($this->request->data['function']);

					unset($this->request->data['saveimage']);

					unset($this->request->data['title']);

					unset($this->request->data['post_url']);

					unset($this->request->data['pincode']);

					unset($this->request->data['state']);

					unset($this->request->data['hobbies']);

					unset($this->request->data['district']);

					unset($this->request->data['description']);

					unset($this->request->data['past_experience']);

					unset($this->request->data['industry']);

					unset($this->request->data['role']);

					unset($this->request->data['job_location']);

					unset($this->request->data['skill']);

					unset($this->request->data['job_type']);

					unset($this->request->data['work_time']);

					unset($this->request->data['description1']);

					unset($this->request->data['objquestion']);

					unset($this->request->data['answer']);

					unset($this->request->data['right_answer']);



				} else if ($this->request->data['post_type'] == 'Q&A') {



					if (!empty($this->request->data['postimage']['name'])) {

						$clg_logo = $file = $this->request->data['postimage'];

						$clg_logo_name = $file = $this->request->data['postimage']['name'];

						$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

						//$save_file = time().$clg_logo_name;



						//@move_uploaded_file($file,"img/Post/".$save_file);

						$save_clglogo = time() . $clg_logo_name;

						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						$this->request->data['image'] = $save_file;

						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

							unlink('img/Post/' . $this->request->data['saveimage']);

						}

						$this->request->data['image'] = $save_file;

					}



					$objquestion = $this->request->data['objquestion'];

					$answer = $this->request->data['answer'];

					$right_answer = $this->request->data['right_answer'];



					if (isset($answer)) {

						$this->request->data['answer'] = implode(';;', $answer);

					}

					if (isset($right_answer)) {

						$this->request->data['right_answer'] = implode(';;', $right_answer);

					}

					$this->request->data['category'] = $this->request->data['qna_category'];

					unset($this->request->data['qna_category']);



					unset($this->request->data['client_to_manage']);

					unset($this->request->data['capacity']);

					unset($this->request->data['working_days']);

					unset($this->request->data['fixed_salary']);

					unset($this->request->data['variable_sallery']);

					unset($this->request->data['postimage']);

					unset($this->request->data['question']);

					unset($this->request->data['function']);

					unset($this->request->data['saveimage']);

					unset($this->request->data['option']);

					unset($this->request->data['rating']);

					unset($this->request->data['title']);

					unset($this->request->data['post_url']);

					unset($this->request->data['pincode']);

					unset($this->request->data['state']);

					unset($this->request->data['hobbies']);

					unset($this->request->data['district']);

					unset($this->request->data['description']);

					unset($this->request->data['past_experience']);

					unset($this->request->data['industry']);

					unset($this->request->data['role']);

					unset($this->request->data['job_location']);

					unset($this->request->data['skill']);

					unset($this->request->data['job_type']);

					unset($this->request->data['work_time']);

					unset($this->request->data['description1']);



				} else if ($this->request->data['post_type'] == 'Job' || $this->request->data['post_type'] == 'Internship') {



					unset($this->request->data['title']);

					unset($this->request->data['post_url']);

					unset($this->request->data['pincode']);

					unset($this->request->data['state']);

					unset($this->request->data['district']);

					unset($this->request->data['description']);

					$this->request->data['description'] = $this->request->data['description1'];

					$this->request->data['title'] = $this->request->data['title1'];



					if (!empty($this->request->data['postimage']['name'])) {

						$clg_logo = $file = $this->request->data['postimage'];

						$clg_logo_name = $file = $this->request->data['postimage']['name'];

						$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

						$save_clglogo = time() . $clg_logo_name;



						//	$save_file=$this->uploadImage($save_clglogo,$clg_logo_path,'img/Post/','650','250');



						if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

							unlink('img/Post/' . $this->request->data['saveimage']);

						}

						//$save_file=$save_clglogo;

						//@move_uploaded_file($file,"img/Post/".$save_file);

						$save_clglogo = time() . $clg_logo_name;

						$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

						$this->request->data['image'] = $save_file;

					}

					$this->request->data['category'] = '';

				} else {

					unset($this->request->data['status']);

					//$this->request->data['status']=$this->request->data['status1']; 

					unset($this->request->data['industry']);

					unset($this->request->data['function']);

					unset($this->request->data['role']);

					unset($this->request->data['past_experience']);

					unset($this->request->data['job_location']);

					unset($this->request->data['skill']);

					unset($this->request->data['job_type']);

					unset($this->request->data['client_to_manage']);

					unset($this->request->data['capacity']);

					unset($this->request->data['salary']);

					unset($this->request->data['cutoff']);

					unset($this->request->data['working_days']);

					unset($this->request->data['work_time']);



					$this->request->data['category'] = '';

				}

				unset($this->request->data['postimage']);

				unset($this->request->data['saveimage']);



				if (isset($this->request->data['Preview'])) {

					unset($this->request->data['status']);

					$this->request->data['status'] = 0;

				} else {

					// if(empty($this->request->data['id'])){

					// unset($this->request->data['status']);

					$this->request->data['status'] = 1;

					// }

				}



				//print_r($this->request->data);die;

				//if($type!="copy"){  unset($this->request->data['id']); }

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {
					if (empty($this->request->data['id'])) {

						$pageids = $this->get_postid($this->request->data['id']);

					} else {

						$respage2 = $this->Tbl_posts->find("all", array('order' => array('id' => 'desc'), 'fields' => array('id')));

						$respage2->hydrate(false);

						$cmspage3 = $respage2->first();

						$pageids = $cmspage3['id'];



						//Recruiter credit deductions......................	



						$resded['cmp_id'] = $cmpid;

						$resded['post_id'] = $pageids;

						$resded['credit'] = 1;

						$datadedct = $this->Recruiter_credit_deductions->newEntity($resded);
						// $this->Recruiter_credit_deductions->save($datadedct);



					}

					if (
						($this->request->data['post_type'] == 'Job' || $this->request->data['post_type'] == 'Internship') &&
						$this->request->data['status'] == 1
					) {
						$postType = $this->request->data['post_type']; // Job / Internship
						$job_title = $this->request->data['title'] ?? 'New Post';
						$currentTime = date('h:i A'); // Example: 10:23 PM

						$notificationTitle = "LifeSet | {$postType}s ";
						$notificationBody = " {$job_title}";

						$facultyTokens = $this->Tbl_faculty_members->find('list', [
							'keyField' => 'id',
							'valueField' => 'fcm_token',
							'conditions' => ['fcm_token IS NOT' => null]
						])->toArray();

						$this->sendNotificationsToFcmTokens($facultyTokens, $notificationTitle, $notificationBody);
					}


					$saveseopost = array();

					//if($type!="copy"){ 	

					if (!empty($this->request->data['seo_id'])) {

						$saveseopost['id'] = $this->request->data['seo_id'];

					}

					// }

					$saveseopost['post_id'] = $pageids;

					$saveseopost['seo_title'] = $seo_title;

					$saveseopost['seo_keyword'] = $seo_keyword;

					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);

					$this->Tbl_post_seos->save($postToSaveseo);



					$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

					if (isset($this->request->data['Preview'])) {

						//return $this->redirect($_SERVER['HTTP_REFERER']);die;



						return $this->redirect(webURL . 'add-edit-post?type=update&&pageid=' . $pageids);

					} else {

						$this->loadModel('Tbl_company_accs');



						/* email sending start here*/

						$upid = $_SESSION['company_accs']['id'];



						$cmp = $this->Tbl_company_accs->find("all", array('conditions' => array('id' => $upid)));

						$cmp->hydrate(false);

						$cmpdata = $cmp->first();

						$this->loadModel('Tbl_posts');



						$pid = $_GET['pageid'];



						$post_d = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pid)));

						$post_d->hydrate(false);

						$cmpost_d = $post_d->first();

						$post_title = $cmpost_d['title'];

						$to = $cmpdata['email'];

						$subject = "New Job Post Successfully On LifeSet";

						$headers = "MIME-Version: 1.0\r\n";

						$headers .= 'From: info@lifeset.co.in' . "\r\n";

						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";





						$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>	A Student Networking Site from Bharat</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $_SESSION['company_accs']['name'] . ",</h2>

                              <h2 style='font-size:30px; font-weight:normal;'>Thank you for posting on LifeSet.</h2>

                               

                                    <!-- Content Section start here ------------------- -->

                                

                              <p style='padding:20px 50px; font-size:18px; line-height:23px font-weight:bold;'><a href='https://lifeset.co.in/admin-post-detail/" . $pid . "'>" . $post_title . "</a></p>

                              <p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'><a href='https://lifeset.co.in/post-list'>View all Jobs</a></h3></p>

                        		<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>We will share the responses against your post everyday in the morning.</h3></p>

                         			

<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Regards,</p>

<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Team LifeSet</p>



                         	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in' />

                              

                              </td>

                          </tr>

                          <tr style='background:#ededed; color:#000;'>

                            <td align='center' style='padding:30px'>

                            

                                        <!-- Action Button Section start here ------------------- -->

                            <a href='https://lifeset.co.in/company-dashboard' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>View Dashboard</a></td>

                          </tr>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";



						mail($to, $subject, $body, $headers);

						/* email sending end here */

						return $this->redirect(webURL . 'post-list');

					}

				}

			}

		}

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'post-list');
					die;

				}

			}

		}

	}



	public function addeditPost4($id = null)
	{



	}

	public function addGkPost($id = null)
	{
		$this->loadModel('Tbl_gk_posts');
		$this->loadModel('Tbl_categories');
		$this->loadModel('Tbl_subcategories');
		$this->loadModel('Tbl_sections');

		$gkPost = $id
			? $this->Tbl_gk_posts->find()->where(['id' => $id])->first()
			: $this->Tbl_gk_posts->newEntity();

		$categories = $this->Tbl_categories->find('list')->toArray();
		$subcategories = $this->Tbl_subcategories->find('list')->toArray();
		$sections = $this->Tbl_sections->find('list')->toArray();

		$this->set(compact('gkPost', 'categories', 'subcategories', 'sections'));
	}


	public function saveGkPost($postData)
	{
		$this->loadModel('PostGk');  // only post_gk table use karvanu

		// Check ID → edit or new
		$gkPost = !empty($postData['id'])
			? $this->PostGk->get($postData['id'])
			: $this->PostGk->newEntity();

		// Auto set created / updated timestamps
		if (!empty($postData['id'])) {
			$postData['updated'] = date('Y-m-d H:i:s');
		} else {
			$postData['created'] = date('Y-m-d H:i:s');
			$postData['updated'] = date('Y-m-d H:i:s');
		}

		// Patch entity with form data
		$gkPost = $this->PostGk->patchEntity($gkPost, $postData);

		// Save to DB
		if ($this->PostGk->save($gkPost)) {
			$this->Flash->success(__('GK Post saved successfully.'));
			return $this->redirect(['action' => 'index']);
		} else {
			$this->Flash->error(__('Unable to save GK Post. Please try again.'));
		}
	}



	// public function addeditPost3()
	// {
	// 	$this->loadModel('PostGk');
	// 	$this->loadModel('PostCat'); // category master table

	// 	$pageid = $this->request->getQuery('id') ?? '';
	// 	$type = $this->request->getQuery('type') ?? '';

	// 	// fetch for edit
	// 	$respage = $this->PostGk->find("all", [
	// 		'conditions' => ['id' => $pageid]
	// 	])->enableHydration(false);

	// 	$cmspage = $respage->first();
	// 	$this->set('viewData', $cmspage);

	// 	// ----- categories -----
	// 	$categories = $this->PostCat->find('list', [
	// 		'keyField' => 'category',
	// 		'valueField' => 'category'
	// 	])->distinct(['category'])->toArray();

	// 	// selected category & subcategory from querystring
	// 	$selectedCategory = $this->request->getQuery('category') ?? null;
	// 	$selectedSubcategory = $this->request->getQuery('subcategory') ?? null;

	// 	// ----- subcategories -----
	// 	$subcategories = [];
	// 	if ($selectedCategory) {
	// 		$subcategories = $this->PostCat->find('list', [
	// 			'keyField' => 'sub_category',
	// 			'valueField' => 'sub_category'
	// 		])
	// 			->where(['category' => $selectedCategory])
	// 			->distinct(['sub_category'])
	// 			->toArray();
	// 	}

	// 	// ----- sections -----
	// 	$sections = [];
	// 	if ($selectedSubcategory) {
	// 		$sections = $this->PostCat->find('list', [
	// 			'keyField' => 'section',
	// 			'valueField' => 'section'
	// 		])
	// 			->where(['sub_category' => $selectedSubcategory])
	// 			->distinct(['section'])
	// 			->toArray();
	// 	}

	// 	$this->set(compact('categories', 'subcategories', 'sections', 'selectedCategory', 'selectedSubcategory'));

	// 	// ----- save process -----
	// 	if ($this->request->is('post')) {
	// 		$data = $this->request->getData();

	// 		// create slug
	// 		if (!empty($data['title'])) {
	// 			$data['slug'] = strtolower(
	// 				trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title']))
	// 			);
	// 		}

	// 		if (!empty($data['id'])) {
	// 			$data['updated'] = date('Y-m-d H:i:s');
	// 		} else {
	// 			$data['created'] = date('Y-m-d H:i:s');
	// 			$data['updated'] = date('Y-m-d H:i:s');
	// 		}

	// 		// ---- IMAGE UPLOAD (Exam-style logic) ----
	// 		if (!empty($data['image']['name'])) {
	// 			$imgName = time() . "_" . $data['image']['name'];    // new filename
	// 			$tmpPath = $data['image']['tmp_name'];

	// 			// save new file
	// 			$saveFile = $this->uploadpostImage($imgName, $tmpPath, 'img/Post/', '600');
	// 			$data['image'] = $saveFile;

	// 			// delete old file if exists
	// 			if (!empty($data['saveimage']) && file_exists('img/Post/' . $data['saveimage'])) {
	// 				unlink('img/Post/' . $data['saveimage']);
	// 			}
	// 		} else {
	// 			// retain old image if not changed
	// 			$data['image'] = $data['saveimage'] ?? '';
	// 		}

	// 		// ---- save in GK table ----
	// 		$entity = $this->PostGk->newEntity($data);
	// 		if ($this->PostGk->save($entity)) {
	// 			$this->Flash->success('GK Post successfully saved', ['key' => 'acc_alert']);

	// 			// Preview redirect
	// 			if (isset($data['Preview'])) {
	// 				$pageids = !empty($data['id']) ? $data['id'] : $entity->id;
	// 				return $this->redirect(webURL . 'add-post-gk?type=update&&id=' . $pageids);
	// 			} else {
	// 				// normal save redirect
	// 				return $this->redirect(webURL . 'post-cat');
	// 			}
	// 		}
	// 	}

	// 	// ----- delete process -----
	// 	if ($type == "delete" && !empty($pageid)) {
	// 		$content = $this->PostGk->get($pageid);
	// 		if ($this->PostGk->delete($content)) {
	// 			$this->Flash->success('GK Post deleted successfully', ['key' => 'acc_alert']);
	// 			return $this->redirect(webURL . 'post-gk-list');
	// 		}
	// 	}
	// }

	public function getSections()
	{
		$this->loadModel('PostCat');
		$subcategory = $this->request->getQuery('subcategory');

		$sections = $this->PostCat->find('list', [
			'keyField' => 'section',
			'valueField' => 'section'
		])
			->where(['sub_category' => $subcategory])
			->distinct(['section'])
			->toArray();

		$this->set([
			'sections' => $sections,
			'_serialize' => ['sections']
		]);
	}

public function getSubcategories1() {
    $this->autoRender = false;
    $categoryId = $this->request->getQuery('category_id');

    $subcategories = $this->SubCategory->find('list', [
        'keyField' => 'id',
        'valueField' => 'sub_category_name'
    ])->where(['category_id' => $categoryId])->toArray();

    echo json_encode(['subcategories' => $subcategories]);
}

public function getSections1()
{
    $this->autoRender = false;
    $this->loadModel('PostGkSection');  // jema section_name save che

    $subCategoryId = $this->request->getQuery('sub_category_id');
    $sections = $this->PostGkSection->find('list', [
        'keyField'   => 'id',
        'valueField' => 'section_name'
    ])
    ->where(['sub_category_id' => $subCategoryId])
    ->toArray();

    foreach ($sections as $id => $name) {
        echo "<option value='{$id}'>{$name}</option>";
    }
    exit;
}

	// public function addeditgkPost()
	// {
	// 	$this->loadModel('PostGk');
	// 	$this->loadModel('PostGkSection');

	// 	$pageid = $this->request->getQuery('pageid') ?? '';
	// 	$type = $this->request->getQuery('type') ?? '';

	// 	// ---- entity prepare (new/edit) ----
	// 	if (!empty($pageid)) {
	// 		$entity = $this->PostGk->get($pageid);   // edit case
	// 	} else {
	// 		$entity = $this->PostGk->newEntity([]); // new case
	// 	}

	// 	// ----- categories -----
	// 	$categories = $this->PostGkSection->find('list', [
	// 		'keyField' => 'category',
	// 		'valueField' => 'category'
	// 	])->distinct(['category'])->toArray();

	// 	// selected category & subcategory
	// 	$selectedCategory = $this->request->getQuery('category') ?? $entity->category ?? null;
	// 	$selectedSubcategory = $this->request->getQuery('subcategory') ?? $entity->subcategory ?? null;

	// 	// preserve title if page reload
	// 	$titleValue = $this->request->getData('title')
	// 		?? $this->request->getQuery('title')
	// 		?? $entity->title ?? '';

	// 	// ----- subcategories -----
	// 	$subcategories = [];
	// 	if ($selectedCategory) {
	// 		$subcategories = $this->PostGkSection->find('list', [
	// 			'keyField' => 'sub_category',
	// 			'valueField' => 'sub_category'
	// 		])
	// 			->where(['category' => $selectedCategory])
	// 			->distinct(['sub_category'])
	// 			->toArray();
	// 	}

	// 	// ----- sections -----
	// 	$sections = [];
	// 	if ($selectedSubcategory) {
	// 		$sections = $this->PostGkSection->find('list', [
	// 			'keyField' => 'section',
	// 			'valueField' => 'section'
	// 		])
	// 			->where(['sub_category' => $selectedSubcategory])
	// 			->distinct(['section'])
	// 			->toArray();
	// 	}

	// 	// ----- DELETE CASE -----
	// 	if ($type == "delete" && !empty($pageid)) {
	// 		$cmspage = $this->PostGk->get($pageid);

	// 		if ($this->PostGk->delete($cmspage)) {
	// 			// delete image file if exists
	// 			if (!empty($cmspage->image) && file_exists('img/Post/' . $cmspage->image)) {
	// 				unlink('img/Post/' . $cmspage->image);
	// 			}

	// 			// delete document file if exists
	// 			if (!empty($cmspage->documnet_link) && file_exists(WWW_ROOT . 'files/documents/' . $cmspage->documnet_link)) {
	// 				unlink(WWW_ROOT . 'files/documents/' . $cmspage->documnet_link);
	// 			}

	// 			$this->Flash->success('Post successfully deleted', ['key' => 'acc_alert']);
	// 			return $this->redirect(webURL . 'post-gk');
	// 		} else {
	// 			$this->Flash->error('Delete failed, please try again.', ['key' => 'acc_alert']);
	// 		}
	// 	}

	// 	// ----- save / preview / publish process -----
	// 	if ($this->request->is(['post', 'put'])) {
	// 		$data = $this->request->getData();
	// 		$data['post_type'] = 'GK';

	// 		// slug generate
	// 		if (!empty($data['title'])) {
	// 			$data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'])));
	// 		}

	// 		if (!empty($entity->id)) {
	// 			$data['updated'] = date('Y-m-d H:i:s');
	// 		} else {
	// 			$data['created'] = date('Y-m-d H:i:s');
	// 			$data['updated'] = date('Y-m-d H:i:s');
	// 		}

	// 		// ---- IMAGE UPLOAD ----
	// 		if (!empty($data['image']['name'])) {
	// 			$imgName = time() . "_" . $data['image']['name'];
	// 			$tmpPath = $data['image']['tmp_name'];

	// 			$saveFile = $this->uploadpostImage($imgName, $tmpPath, 'img/Post/', '600');
	// 			$data['image'] = $saveFile;

	// 			if (!empty($data['saveimage']) && file_exists('img/Post/' . $data['saveimage'])) {
	// 				unlink('img/Post/' . $data['saveimage']);
	// 			}
	// 		} else {
	// 			$data['image'] = $data['saveimage'] ?? $entity->image ?? '';
	// 		}

	// 		// ---- DOCUMENT UPLOAD ----
	// 		if (!empty($data['doc_file']['name'])) {
	// 			$docName = time() . "_" . $data['doc_file']['name'];
	// 			$tmpPath = $data['doc_file']['tmp_name'];

	// 			$uploadPath = WWW_ROOT . 'files/documents/' . $docName;
	// 			if (!file_exists(WWW_ROOT . 'files/documents/')) {
	// 				mkdir(WWW_ROOT . 'files/documents/', 0777, true);
	// 			}

	// 			if (move_uploaded_file($tmpPath, $uploadPath)) {
	// 				$data['documnet_link'] = $docName;
	// 			}

	// 			if (!empty($data['savedoc']) && file_exists(WWW_ROOT . 'files/documents/' . $data['savedoc'])) {
	// 				unlink(WWW_ROOT . 'files/documents/' . $data['savedoc']);
	// 			}
	// 		} else {
	// 			$data['documnet_link'] = $data['savedoc'] ?? $entity->documnet_link ?? '';
	// 		}

	// 		// ---- Patch Entity ----
	// 		$entity = $this->PostGk->patchEntity($entity, $data);

	// 		// ---- PUBLISH ----
	// 		if (isset($data['Publish'])) {
	// 			$data['status'] = 1;
	// 			$entity = $this->PostGk->patchEntity($entity, $data);

	// 			if ($this->PostGk->save($entity)) {
	// 				$this->Flash->success('GK Post successfully published', ['key' => 'acc_alert']);
	// 				return $this->redirect(webURL . 'post-gk');
	// 			}
	// 		}
	// 		// ---- PREVIEW ----
	// 		else {
	// 			if ($this->PostGk->save($entity)) {
	// 				$pageids = $entity->id;
	// 				$this->Flash->success('Preview generated below', ['key' => 'acc_alert']);

	// 				// set viewData
	// 				$viewData = $this->PostGk->get($pageids);
	// 				$this->set('viewData', $viewData);

	// 				return $this->redirect(webURL . 'add-edit-gk-post?type=update&&pageid=' . $pageids);
	// 			}
	// 		}
	// 	}

	// 	// set entity + viewData
	// 	$viewData = !empty($pageid) ? $entity : null;
	// 	$this->set(compact(
	// 		'entity',
	// 		'categories',
	// 		'subcategories',
	// 		'sections',
	// 		'selectedCategory',
	// 		'selectedSubcategory',
	// 		'viewData',
	// 		'titleValue'
	// 	));
	// }


	public function addeditgkPost()
{
    $this->loadModel('PostGk');
    $this->loadModel('PostGkSection');

    $pageid = $this->request->getQuery('pageid') ?? '';
    $type = $this->request->getQuery('type') ?? '';

    // ---- entity prepare (new/edit) ----
    if (!empty($pageid)) {
        $entity = $this->PostGk->get($pageid);   // edit case
    } else {
        $entity = $this->PostGk->newEntity([]); // new case
    }

    // ----- categories (from PostGkSection table) -----
    $categories = $this->PostGkSection->find('list', [
        'keyField' => 'category_id',
        'valueField' => 'category_id'
    ])->distinct(['category_id'])->toArray();

    // selected category & subcategory
    $selectedCategory = $this->request->getQuery('category_id') ?? $entity->category_id ?? null;
    $selectedSubcategory = $this->request->getQuery('sub_category_id') ?? $entity->sub_category_id ?? null;

    // preserve title if page reload
    $titleValue = $this->request->getData('title')
        ?? $this->request->getQuery('title')
        ?? $entity->title ?? '';

    // ----- subcategories -----
    $subcategories = [];
    if ($selectedCategory) {
        $subcategories = $this->PostGkSection->find('list', [
            'keyField' => 'sub_category_id',
            'valueField' => 'sub_category_id'
        ])
            ->where(['category_id' => $selectedCategory])
            ->distinct(['sub_category_id'])
            ->toArray();
    }

    // ----- sections -----
    $sections = [];
    if ($selectedSubcategory) {
        $sections = $this->PostGkSection->find('list', [
            'keyField' => 'id',
            'valueField' => 'section_name'
        ])
            ->where(['sub_category_id' => $selectedSubcategory])
            ->distinct(['section_name'])
            ->toArray();
    }

    // ----- DELETE CASE -----
    if ($type == "delete" && !empty($pageid)) {
        $cmspage = $this->PostGk->get($pageid);

        if ($this->PostGk->delete($cmspage)) {
            // delete image file if exists
            if (!empty($cmspage->image) && file_exists('img/Post/' . $cmspage->image)) {
                unlink('img/Post/' . $cmspage->image);
            }

            // delete document file if exists
            if (!empty($cmspage->documnet_link) && file_exists(WWW_ROOT . 'files/documents/' . $cmspage->documnet_link)) {
                unlink(WWW_ROOT . 'files/documents/' . $cmspage->documnet_link);
            }

            $this->Flash->success('Post successfully deleted', ['key' => 'acc_alert']);
            return $this->redirect(webURL . 'post-gk');
        } else {
            $this->Flash->error('Delete failed, please try again.', ['key' => 'acc_alert']);
        }
    }

    // ----- save / preview / publish process -----
    if ($this->request->is(['post', 'put'])) {
        $data = $this->request->getData();
        $data['post_type'] = 'GK';

        // slug generate
        if (!empty($data['title'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'])));
        }

        if (!empty($entity->id)) {
            $data['updated'] = date('Y-m-d H:i:s');
        } else {
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
        }

        // ---- IMAGE UPLOAD ----
        if (!empty($data['image']['name'])) {
            $imgName = time() . "_" . $data['image']['name'];
            $tmpPath = $data['image']['tmp_name'];

            $saveFile = $this->uploadpostImage($imgName, $tmpPath, 'img/Post/', '600');
            $data['image'] = $saveFile;

            if (!empty($data['saveimage']) && file_exists('img/Post/' . $data['saveimage'])) {
                unlink('img/Post/' . $data['saveimage']);
            }
        } else {
            $data['image'] = $data['saveimage'] ?? $entity->image ?? '';
        }

        // ---- DOCUMENT UPLOAD ----
        if (!empty($data['doc_file']['name'])) {
            $docName = time() . "_" . $data['doc_file']['name'];
            $tmpPath = $data['doc_file']['tmp_name'];

            $uploadPath = WWW_ROOT . 'files/documents/' . $docName;
            if (!file_exists(WWW_ROOT . 'files/documents/')) {
                mkdir(WWW_ROOT . 'files/documents/', 0777, true);
            }

            if (move_uploaded_file($tmpPath, $uploadPath)) {
                $data['documnet_link'] = $docName;
            }

            if (!empty($data['savedoc']) && file_exists(WWW_ROOT . 'files/documents/' . $data['savedoc'])) {
                unlink(WWW_ROOT . 'files/documents/' . $data['savedoc']);
            }
        } else {
            $data['documnet_link'] = $data['savedoc'] ?? $entity->documnet_link ?? '';
        }

        // ---- Patch Entity ----
        $entity = $this->PostGk->patchEntity($entity, $data);

        // ---- PUBLISH ----
        if (isset($data['Publish'])) {
            $data['status'] = 1;
            $entity = $this->PostGk->patchEntity($entity, $data);

            if ($this->PostGk->save($entity)) {
                $this->Flash->success('GK Post successfully published', ['key' => 'acc_alert']);
                return $this->redirect(webURL . 'post-gk');
            }
        }
        // ---- PREVIEW ----
        else {
            if ($this->PostGk->save($entity)) {
                $pageids = $entity->id;
                $this->Flash->success('Preview generated below', ['key' => 'acc_alert']);

                // set viewData
                $viewData = $this->PostGk->get($pageids);
                $this->set('viewData', $viewData);

                return $this->redirect(webURL . 'add-edit-gk-post?type=update&&pageid=' . $pageids);
            }
        }
    }

    // set entity + viewData
    $viewData = !empty($pageid) ? $entity : null;
    $this->set(compact(
        'entity',
        'categories',
        'subcategories',
        'sections',
        'selectedCategory',
        'selectedSubcategory',
        'viewData',
        'titleValue'
    ));
}




	// public function addeditPost3()
// {
//     $this->loadModel('PostGk');
//     $this->loadModel('PostGkSection');

	//     $pageid = $this->request->getQuery('id') ?? '';
//     $type   = $this->request->getQuery('type') ?? '';

	//     // fetch edit data
//     $respage = $this->PostGk->find("all", [
//         'conditions' => ['id' => $pageid]
//     ])->enableHydration(false);

	//     $cmspage = $respage->first();
//     $this->set('viewData', $cmspage);

	//     // ----- categories -----
//     $categories = $this->PostCat->find('list', [
//         'keyField'   => 'category',
//         'valueField' => 'category'
//     ])->distinct(['category'])->toArray();

	//     // selected category & subcategory from querystring
//     $selectedCategory    = $this->request->getQuery('category') ?? null;
//     $selectedSubcategory = $this->request->getQuery('subcategory') ?? null;

	//     // ----- subcategories -----
//     $subcategories = [];
//     if ($selectedCategory) {
//         $subcategories = $this->PostCat->find('list', [
//             'keyField'   => 'sub_category',
//             'valueField' => 'sub_category'
//         ])
//         ->where(['category' => $selectedCategory])
//         ->distinct(['sub_category'])
//         ->toArray();
//     }

	//     // ----- sections -----
//     $sections = [];
//     if ($selectedSubcategory) {
//         $sections = $this->PostCat->find('list', [
//             'keyField'   => 'section',
//             'valueField' => 'section'
//         ])
//         ->where(['sub_category' => $selectedSubcategory])
//         ->distinct(['section'])
//         ->toArray();
//     }

	//     $this->set(compact('categories', 'subcategories', 'sections', 'selectedCategory', 'selectedSubcategory'));

	//     // save process
//     if ($this->request->is('post')) {
//         $data = $this->request->getData();

	//         // create slug
//         if (!empty($data['title'])) {
//             $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'])));
//         }

	//         if (!empty($data['id'])) {
//             $data['updated'] = date('Y-m-d H:i:s');
//         } else {
//             $data['created'] = date('Y-m-d H:i:s');
//             $data['updated'] = date('Y-m-d H:i:s');
//         }

	//         $entity = $this->PostGk->newEntity($data);
//         if ($this->PostGk->save($entity)) {
//             $this->Flash->success('GK Post successfully saved', ['key' => 'acc_alert']);
//             return $this->redirect(webURL . 'post-gk-list');
//         }
//     }
// }


	public function wallShare($pageid = 0)
	{

		$this->loadModel('Tbl_posts');



		$this->viewBuilder()->layout('home');

		$cmpid = $this->request->session()->read("company_accs.id");

		$memberid = $this->request->session()->read("Tbl_faculty_members.id");

		$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		if (!empty($cmpid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid, 'status' => 1), 'fields' => array('id', 'title', 'image', 'description', 'question', 'objquestion', 'post_type')));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else if (!empty($collid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $collid, 'id' => $pageid, 'status' => 1), 'fields' => array('id', 'title', 'image', 'description', 'question', 'objquestion', 'post_type')));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid, 'status' => 1), 'fields' => array('id', 'title', 'image', 'description', 'question', 'objquestion', 'post_type')));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		}

		$this->set('blogs', $cmspage);

	}

	public function deletequizpostList()
	{

		$this->loadModel('Tbl_posts');

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		if ($type == "delete") {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'quiz-post-list');
					die;

				}

			}

		}

	}

	public function deletereviewpostList()
	{

		$this->loadModel('Tbl_posts');

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		if ($type == "delete") {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'review-post-list');
					die;

				}

			}

		}

	}

	public function jobpostList()
	{

		$this->loadModel('Tbl_posts');

		$respage = '';

		$type = 'Job';

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => $type), 'order' => array('updated' => 'desc')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('BlogPage', $cmspage);





	}

	public function old_addeditjobPost()
	{

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_post_seos');

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$type = 'Job';

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid, 'post_type' => $type)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		$cmppage = $this->Tbl_company_accs->find("all", array('conditions' => array('type' => 'Demo'), 'order' => array('id' => 'desc')));

		$cmppage->hydrate(false);

		$datacmppage = $cmppage->toArray();



		$this->set('companylist', $datacmppage);



		if ($this->request->is('post')) {

			if (isset($this->request->data['Deactive'])) {

				$this->request->data['status'] = 0;

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					$this->Flash->success('Data successfully updated', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'job-post-list');
					die;

				}

			}

			$this->request->data['college_id'] = '';

			//$this->request->data['company_id']='';  

			//	$this->request->data['category']='';  

			if (!empty($this->request->data['company_id'])) {

				$cmpid = $this->request->data['company_id'];

				$rescmppage = $this->Tbl_company_accs->find("all", array('conditions' => array('id' => $cmpid)));

				$rescmppage->hydrate(false);

				$datacmp = $rescmppage->first();

				$this->request->data['company_name'] = $datacmp['name'] ?? '';

			}

			$seo_title = $this->request->data['seo_title'];

			$seo_keyword = $this->request->data['seo_keyword'];

			$seo_description = $this->request->data['seo_description'];



			$savedata = array();

			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['created'] = date('Y-m-d h:i:s a');

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			}

			unset($this->request->data['post_url']);

			unset($this->request->data['pincode']);

			unset($this->request->data['state']);

			unset($this->request->data['district']);

			unset($this->request->data['description']);

			$this->request->data['description'] = $this->request->data['description1'];

			$this->request->data['title'] = $this->request->data['title'];

			$job_title = $this->request->data['title'];

			if (!empty($this->request->data['postimage']['name'])) {

				$clg_logo = $file = $this->request->data['postimage'];

				$clg_logo_name = $file = $this->request->data['postimage']['name'];

				$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

				$save_clglogo = time() . $clg_logo_name;



				//	$save_file=$this->uploadImage($save_clglogo,$clg_logo_path,'img/Post/','650','250');



				if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

					unlink('img/Post/' . $this->request->data['saveimage']);

				}

				//$save_file=$save_clglogo;

				//@move_uploaded_file($file,"img/Post/".$save_file);

				$save_clglogo = time() . $clg_logo_name;

				$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

				$this->request->data['image'] = $save_file;

			}



			unset($this->request->data['postimage']);

			unset($this->request->data['saveimage']);



			if (isset($this->request->data['Preview'])) {

				unset($this->request->data['status']);

				$this->request->data['status'] = 0;

			} else {

				$this->request->data['status'] = 1;

			}

			$this->request->data['category'] = '';

			$this->request->data['post_type'] = 'Job';

			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				if (isset($this->request->data['Preview'])) {

					if (!empty($this->request->data['id'])) {

						$pageids = $this->request->data['id'];

					} else {

						$respage2 = $this->Tbl_posts->find("all", array('order' => array('id' => 'desc'), 'fields' => array('id')));

						$respage2->hydrate(false);

						$cmspage3 = $respage2->first();

						$pageids = $cmspage3['id'];

					}

					//print_r($this->request->data['id']);die;

					$saveseopost = array();

					if (!empty($this->request->data['seo_id'])) {

						$saveseopost['id'] = $this->request->data['seo_id'];

					}

					$saveseopost['post_id'] = $pageids;

					$saveseopost['seo_title'] = $seo_title;

					$saveseopost['seo_keyword'] = $seo_keyword;

					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);

					$this->Tbl_post_seos->save($postToSaveseo);



					return $this->redirect(webURL . 'add-edit-job-post?type=update&&pageid=' . $pageids);
					die;

				} else {



					return $this->redirect(webURL . 'job-post-list');
					die;

				}

			}

		}

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'job-post-list');
					die;

				}

			}

		}

	}



	//   public function addeditjobPost()

	// 	{

	// 	    $this->loadModel('Tbl_company_accs');

	// 	    $this->loadModel('Tbl_posts');

	// 	    $this->loadModel('Tbl_post_seos');

	// 	    $this->loadModel('Tbl_faculty_members'); // Load the faculty members model



	// 	    if (isset($_GET['pageid'])) {

	// 	        $pageid = $_GET['pageid'];

	// 	    } else {

	// 	        $pageid = '';

	// 	    }



	// 	    if (isset($_GET['type'])) {

	// 	        $type = $_GET['type'];

	// 	    } else {

	// 	        $type = '';

	// 	    }



	// 	    $type = 'Job';

	// 	    $respage = $this->Tbl_posts->find("all", ['conditions' => ['id' => $pageid, 'post_type' => $type]]);

	// 	    $respage->hydrate(false);

	// 	    $cmspage = $respage->first();

	// 	    $this->set('viewData', $cmspage);



	// 	    $cmppage = $this->Tbl_company_accs->find("all", ['conditions' => ['type' => 'Demo'], 'order' => ['id' => 'desc']]);

	// 	    $cmppage->hydrate(false);

	// 	    $datacmppage = $cmppage->toArray();



	// 	    $this->set('companylist', $datacmppage);



	// 	    if ($this->request->is('post')) {

	// 	        if (isset($this->request->data['Deactive'])) {

	// 	            $this->request->data['status'] = 0;

	// 	            $dataToSave = $this->Tbl_posts->newEntity($this->request->data);

	// 	            if ($this->Tbl_posts->save($dataToSave)) {

	// 	                $this->Flash->success('Data successfully updated', ['key' => 'acc_alert']);

	// 	                return $this->redirect(webURL . 'job-post-list');

	// 	                die;

	// 	            }

	// 	        }

	// 	        $this->request->data['college_id'] = '';



	// 	        if (!empty($this->request->data['company_id'])) {

	// 	            $cmpid = $this->request->data['company_id'];

	// 	            $rescmppage = $this->Tbl_company_accs->find("all", ['conditions' => ['id' => $cmpid]]);

	// 	            $rescmppage->hydrate(false);

	// 	            $datacmp = $rescmppage->first();

	// 	            $this->request->data['company_name'] = $datacmp['name'] ?? '';

	// 	        }

	// 	        $seo_title = $this->request->data['seo_title'];

	// 	        $seo_keyword = $this->request->data['seo_keyword'];

	// 	        $seo_description = $this->request->data['seo_description'];



	// 	        $this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

	// 	        if (!empty($this->request->data['id'])) {

	// 	            $this->request->data['updated'] = date('Y-m-d h:i:s a');

	// 	        } else {

	// 	            $this->request->data['created'] = date('Y-m-d h:i:s a');

	// 	            $this->request->data['updated'] = date('Y-m-d h:i:s a');

	// 	        }

	// 	        $this->request->data['description'] = $this->request->data['description1'];

	// 	        $job_title = $this->request->data['title'];



	// 	        if (!empty($this->request->data['postimage']['name'])) {

	// 	            $clg_logo = $this->request->data['postimage'];

	// 	            $clg_logo_name = $this->request->data['postimage']['name'];

	// 	            $clg_logo_path = $this->request->data['postimage']['tmp_name'];

	// 	            $save_clglogo = time() . $clg_logo_name;



	// 	            if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

	// 	                unlink('img/Post/' . $this->request->data['saveimage']);

	// 	            }

	// 	            $save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

	// 	            $this->request->data['image'] = $save_file;

	// 	        }



	// 	        unset($this->request->data['postimage']);

	// 	        unset($this->request->data['saveimage']);



	// 	        if (isset($this->request->data['Preview'])) {

	// 	            $this->request->data['status'] = 0;

	// 	        } else {

	// 	            $this->request->data['status'] = 1;

	// 	        }



	// 	        $this->request->data['category'] = '';

	// 	        $this->request->data['post_type'] = 'Job';

	// 	        $dataToSave = $this->Tbl_posts->newEntity($this->request->data);

	// 	        if ($this->Tbl_posts->save($dataToSave)) {

	// 	            $this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);



	// 	            // Fetch all faculty members' FCM tokens

	// 	            $facultyTokens = $this->Tbl_faculty_members->find('list', [

	// 	                'keyField' => 'id',

	// 	                'valueField' => 'fcm_token',

	// 	                'conditions' => ['fcm_token IS NOT' => null]

	// 	            ])->toArray();



	// 	            // echo("<pre>");print_r($facultyTokens);exit; 



	// 	            // Send notifications to all faculty members

	// 	            $this->sendNotificationsToFcmTokens($facultyTokens, "New Job Posted: $job_title");



	// 	            if (isset($this->request->data['Preview'])) {

	// 	                if (!empty($this->request->data['id'])) {

	// 	                    $pageids = $this->request->data['id'];

	// 	                } else {

	// 	                    $respage2 = $this->Tbl_posts->find("all", ['order' => ['id' => 'desc'], 'fields' => ['id']]);

	// 	                    $respage2->hydrate(false);

	// 	                    $cmspage3 = $respage2->first();

	// 	                    $pageids = $cmspage3['id'];

	// 	                }

	// 	                $saveseopost = [];

	// 	                if (!empty($this->request->data['seo_id'])) {

	// 	                    $saveseopost['id'] = $this->request->data['seo_id'];

	// 	                }

	// 	                $saveseopost['post_id'] = $pageids;

	// 	                $saveseopost['seo_title'] = $seo_title;

	// 	                $saveseopost['seo_keyword'] = $seo_keyword;

	// 	                $saveseopost['seo_description'] = $seo_description;

	// 	                $postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);

	// 	                $this->Tbl_post_seos->save($postToSaveseo);



	// 	                return $this->redirect(webURL . 'add-edit-job-post?type=update&&pageid=' . $pageids);

	// 	                die;

	// 	            } else {

	// 	                return $this->redirect(webURL . 'job-post-list');

	// 	                die;

	// 	            }





	// 	        }

	// 	    }



	// 	    if ($type == "delete") {

	// 	        if (!empty($cmspage)) {

	// 	            $content = $this->Tbl_posts->get($pageid);

	// 	            if ($this->Tbl_posts->delete($content)) {

	// 	                if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

	// 	                    unlink('img/Post/' . $cmspage['image']);

	// 	                }

	// 	                $this->Flash->success('Data successfully deleted', ['key' => 'acc_alert']);

	// 	                return $this->redirect(webURL . 'job-post-list');

	// 	                die;

	// 	            }

	// 	        }

	// 	    }

	// 	}

	public function addeditjobPost()
	{
		$this->loadModel('Tbl_company_accs');
		$this->loadModel('Tbl_posts');
		$this->loadModel('Tbl_post_seos');
		$this->loadModel('Tbl_faculty_members');

		$pageid = $_GET['pageid'] ?? '';
		$type = $_GET['type'] ?? '';

		$type = 'Job';
		$respage = $this->Tbl_posts->find("all", ['conditions' => ['id' => $pageid, 'post_type' => $type]]);
		$respage->hydrate(false);
		$cmspage = $respage->first();
		$this->set('viewData', $cmspage);

		$cmppage = $this->Tbl_company_accs->find("all", ['conditions' => ['type' => 'Demo'], 'order' => ['id' => 'desc']]);
		$cmppage->hydrate(false);
		$datacmppage = $cmppage->toArray();
		$this->set('companylist', $datacmppage);

		if ($this->request->is('post')) {

			if (isset($this->request->data['Deactive'])) {
				$this->request->data['status'] = 0;
				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);
				if ($this->Tbl_posts->save($dataToSave)) {
					$this->Flash->success('Data successfully updated', ['key' => 'acc_alert']);
					return $this->redirect(webURL . 'job-post-list');
				}
			}

			$this->request->data['college_id'] = '';

			if (!empty($this->request->data['company_id'])) {
				$cmpid = $this->request->data['company_id'];
				$rescmppage = $this->Tbl_company_accs->find("all", ['conditions' => ['id' => $cmpid]]);
				$rescmppage->hydrate(false);
				$datacmp = $rescmppage->first();
				$this->request->data['company_name'] = $datacmp['name'] ?? '';
			}

			$seo_title = $this->request->data['seo_title'];
			$seo_keyword = $this->request->data['seo_keyword'];
			$seo_description = $this->request->data['seo_description'];

			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			if (!empty($this->request->data['id'])) {
				$this->request->data['updated'] = date('Y-m-d h:i:s a');
			} else {
				$this->request->data['created'] = date('Y-m-d h:i:s a');
				$this->request->data['updated'] = date('Y-m-d h:i:s a');
			}

			$this->request->data['description'] = $this->request->data['description1'];
			$job_title = $this->request->data['title'];

			if (!empty($this->request->data['postimage']['name'])) {
				$clg_logo = $this->request->data['postimage'];
				$clg_logo_name = $clg_logo['name'];
				$clg_logo_path = $clg_logo['tmp_name'];
				$save_clglogo = time() . $clg_logo_name;

				if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {
					unlink('img/Post/' . $this->request->data['saveimage']);
				}

				$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');
				$this->request->data['image'] = $save_file;
			}

			unset($this->request->data['postimage']);
			unset($this->request->data['saveimage']);

			if (isset($this->request->data['Preview'])) {
				$this->request->data['status'] = 0;
			} else {
				$this->request->data['status'] = 1;
			}

			$this->request->data['category'] = '';
			$this->request->data['post_type'] = 'Job';

			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {
				$this->Flash->success('Data successfully saved', ['key' => 'acc_alert']);

				// ✅ Send notifications ONLY if published (status == 1)
				if ($this->request->data['status'] == 1) {
					$currentTime = date('h:i A'); // Example: 10:45 PM
					$job_title = $this->request->data['title'] ?? 'New Job';

					$facultyTokens = $this->Tbl_faculty_members->find('list', [
						'keyField' => 'id',
						'valueField' => 'fcm_token',
						'conditions' => ['fcm_token IS NOT' => null]
					])->toArray();

					$title = "LifeSet | Jobs ";
					$body = " {$job_title}";

					$this->sendNotificationsToFcmTokens($facultyTokens, $title, $body);
				}


				if (isset($this->request->data['Preview'])) {
					if (!empty($this->request->data['id'])) {
						$pageids = $this->request->data['id'];
					} else {
						$respage2 = $this->Tbl_posts->find("all", ['order' => ['id' => 'desc'], 'fields' => ['id']]);
						$respage2->hydrate(false);
						$cmspage3 = $respage2->first();
						$pageids = $cmspage3['id'];
					}

					$saveseopost = [];
					if (!empty($this->request->data['seo_id'])) {
						$saveseopost['id'] = $this->request->data['seo_id'];
					}

					$saveseopost['post_id'] = $pageids;
					$saveseopost['seo_title'] = $seo_title;
					$saveseopost['seo_keyword'] = $seo_keyword;
					$saveseopost['seo_description'] = $seo_description;

					$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);
					$this->Tbl_post_seos->save($postToSaveseo);

					return $this->redirect(webURL . 'add-edit-job-post?type=update&&pageid=' . $pageids);
				} else {
					return $this->redirect(webURL . 'job-post-list');
				}
			}
		}

		if ($type == "delete") {
			if (!empty($cmspage)) {
				$content = $this->Tbl_posts->get($pageid);
				if ($this->Tbl_posts->delete($content)) {
					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {
						unlink('img/Post/' . $cmspage['image']);
					}
					$this->Flash->success('Data successfully deleted', ['key' => 'acc_alert']);
					return $this->redirect(webURL . 'job-post-list');
				}
			}
		}
	}


	// private function sendNotificationsToFcmTokens($tokens, $message)
	// {

	// 	$serviceAccountPath = dirname(__DIR__) . '/lifeset-2cdb2-firebase-adminsdk-qn0a1-812d6df01a.json'; // Correct path to your service account file



	// 	$client = new Client();

	// 	$client->setAuthConfig($serviceAccountPath);

	// 	$client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);



	// 	$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];



	// 	// print_r($client);exit;

	// 	$projectId = 'lifeset-2cdb2'; // Replace with your actual project ID

	// 	$url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";



	// 	foreach ($tokens as $token) {

	// 		$fields = [

	// 			'message' => [

	// 				'token' => $token,

	// 				'notification' => [

	// 					'title' => 'Notification',

	// 					'body' => $message,

	// 				],

	// 			],

	// 		];



	// 		$headers = [

	// 			'Authorization: Bearer ' . $accessToken,

	// 			'Content-Type: application/json',

	// 		];



	// 		$ch = curl_init();

	// 		curl_setopt($ch, CURLOPT_URL, $url);

	// 		curl_setopt($ch, CURLOPT_POST, true);

	// 		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	// 		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

	// 		$result = curl_exec($ch);



	// 		if ($result === FALSE) {

	// 			// Handle the error

	// 			echo 'Curl failed: ' . curl_error($ch);

	// 		}



	// 		curl_close($ch);



	// 		// Optionally log the result for each token

	// 		// echo $result;

	// 	}

	// }

	private function sendNotificationsToFcmTokens($tokens, $title, $message)
	{
		$serviceAccountPath = dirname(__DIR__) . '/lifeset-2cdb2-firebase-adminsdk-qn0a1-812d6df01a.json';

		$client = new \Google_Client();
		$client->setAuthConfig($serviceAccountPath);
		$client->addScope('https://www.googleapis.com/auth/cloud-platform');

		$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];

		$projectId = 'lifeset-2cdb2';
		$url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

		foreach ($tokens as $token) {
			$fields = [
				'message' => [
					'token' => $token,
					'notification' => [
						'title' => $title,   // ✅ Dynamic title passed
						'body' => $message,  // ✅ Dynamic body/message
					],
					'android' => [
						'priority' => 'high',
					],
					'apns' => [
						'headers' => [
							'apns-priority' => '10',
						],
					],
				],
			];

			$headers = [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json',
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);

			if ($result === FALSE) {
				echo 'Curl failed: ' . curl_error($ch);
			}

			curl_close($ch);

			// Optional: log result
			// echo $result;
		}
	}






	public function addeditqaPost()
	{

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		$this->loadModel('Tbl_posts');

		$type = 'Q&A';

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid, 'post_type' => $type)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {





			if (isset($this->request->data['Deactive'])) {

				$this->request->data['status'] = 0;

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					$this->Flash->success('Data successfully updated', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'quiz-post-list');
					die;

				}

			}

			$savedata = array();

			//$this->request->data['slug'] =strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title']))); 

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['created'] = date('Y-m-d h:i:s a');

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			}

			if ($this->request->data['post_type'] == 'Q&A') {



				if (!empty($this->request->data['postimage']['name'])) {

					$clg_logo = $file = $this->request->data['postimage'];

					$clg_logo_name = $file = $this->request->data['postimage']['name'];

					$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

					//$save_file = time().$clg_logo_name;



					//@move_uploaded_file($file,"img/Post/".$save_file);

					$save_clglogo = time() . $clg_logo_name;

					$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

					$this->request->data['image'] = $save_file;

					if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

						unlink('img/Post/' . $this->request->data['saveimage']);

					}

					$this->request->data['image'] = $save_file;

				}



				$objquestion = $this->request->data['objquestion'];

				$answer = $this->request->data['answer'];

				$right_answer = $this->request->data['right_answer'];



				if (isset($answer)) {

					$this->request->data['answer'] = implode(';;', $answer);

				}

				if (isset($right_answer)) {

					$this->request->data['right_answer'] = implode(';;', $right_answer);

				}

				$this->request->data['category'] = $this->request->data['qna_category'];



			}

			unset($this->request->data['postimage']);

			unset($this->request->data['saveimage']);



			if (isset($this->request->data['Preview'])) {

				unset($this->request->data['status']);

				$this->request->data['status'] = 0;

			} else {

				$this->request->data['status'] = 1;

			}

			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				if (isset($this->request->data['Preview'])) {

					if (!empty($this->request->data['id'])) {

						$pageids = $this->request->data['id'];

					} else {

						$respage2 = $this->Tbl_posts->find("all", array('order' => array('id' => 'desc'), 'fields' => array('id')));

						$respage2->hydrate(false);

						$cmspage3 = $respage2->first();

						$pageids = $cmspage3['id'];

					}

					return $this->redirect(webURL . 'add-edit-qa-post?type=update&&pageid=' . $pageids);
					die;

				} else {



					return $this->redirect(webURL . 'quiz-post-list');
					die;

				}

			}

		}

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'quiz-post-list');
					die;

				}

			}

		}

	}

	public function exampostList()
	{

		$this->loadModel('Tbl_posts');

		$respage = '';

		$type = 'Exam';

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => $type), 'order' => array('updated' => 'desc')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('BlogPage', $cmspage);

	}

	public function addeditexamPost()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_post_seos');

		$this->loadModel('Tbl_exam_posts');

		$this->loadModel('Tbl_personality_posts');

		$this->loadModel('Tbl_survey_posts');

		$this->loadModel('Tbl_gk_posts');



		$cmpid = $this->request->session()->read("company_accs.id");

		$memberid = $this->request->session()->read("Tbl_faculty_members.id");

		$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		if (!empty($cmpid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else if (!empty($collid)) {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('college_id' => $collid, 'id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		} else {

			$respage = $this->Tbl_posts->find("all", array('conditions' => array('id' => $pageid)));

			$respage->hydrate(false);

			$cmspage = $respage->first();

		}

		$this->set('viewData', $cmspage);



		if ($this->request->is('post')) {



			if (isset($this->request->data['Deactive'])) {

				$this->request->data['status'] = 0;

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				if ($this->Tbl_posts->save($dataToSave)) {

					$this->Flash->success('Data successfully updated', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'exam-post-list');
					die;

				}

			}

			if (!empty($collid)) {

				$this->request->data['college_id'] = $collid;

				$this->request->data['post_by'] = $memberid;

			} else if (!empty($cmpid)) {

				$this->request->data['company_id'] = $cmpid;

				$this->request->data['post_by'] = $cmpid;

				$this->request->data['college_id'] = '';

			} else {

				$this->request->data['college_id'] = '';

				$this->request->data['company_id'] = '';

			}

			$savedata = array();

			$savepost = array();



			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			if (!empty($this->request->data['id'])) {

				$savedata['id'] = $this->request->data['id'];

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			} else {

				$this->request->data['created'] = date('Y-m-d h:i:s a');

				$this->request->data['updated'] = date('Y-m-d h:i:s a');

			}



			$seo_title = $this->request->data['seo_title'];

			$seo_keyword = $this->request->data['seo_keyword'];

			$seo_description = $this->request->data['seo_description'];

			//print_r($this->request->data['category']);die;



			$pid = $this->request->data['id'];



			if (isset($this->request->data['Preview'])) {

				unset($this->request->data['status']);

				$this->request->data['status'] = 0;

			} else {

				$this->request->data['status'] = 1;

			}

			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {



				$postid = $this->get_postid($this->request->data['id']);



				$saveseopost = array();

				if (!empty($this->request->data['seo_id'])) {

					$saveseopost['id'] = $this->request->data['seo_id'];

				}

				$saveseopost['post_id'] = $postid;

				$saveseopost['seo_title'] = $seo_title;

				$saveseopost['seo_keyword'] = $seo_keyword;

				$saveseopost['seo_description'] = $seo_description;

				$postToSaveseo = $this->Tbl_post_seos->newEntity($saveseopost);

				$this->Tbl_post_seos->save($postToSaveseo);



				$savepost['id'] = $this->request->data['examid'];

				$savepost['post_id'] = $postid;

				$savepost['cat_id'] = $this->request->data['exam_cat_id'];



				if ($savepost['cat_id'] == 'Other') {
					$savepost['other_exam_category'] = $this->request->data['other_exam_category'];
					$this->request->data['category'] = '';

				} else {
					$savepost['other_exam_category'] = '';

					$this->request->data['category'] = $this->request->data['exam_cat_id'];
				}







				$savepost['exam_name'] = $this->request->data['exam_name'];

				$savepost['name_of_post'] = $this->request->data['name_of_post'];

				$savepost['exam_level'] = $this->request->data['exam_level'];

				if ($savepost['exam_level'] == 'Other') {
					$savepost['other_exam_level'] = $this->request->data['other_exam_level'];
				} else {
					$savepost['other_exam_level'] = '';
				}

				$savepost['announcement_date'] = $this->request->data['announcement_date'];

				$savepost['last_date_form_filling'] = $this->request->data['last_date_form_filling'];

				$savepost['admit_card'] = $this->request->data['admit_card'];

				$savepost['exam_date'] = $this->request->data['exam_date'];

				$savepost['exam_time'] = $this->request->data['exam_time'];

				$savepost['result'] = $this->request->data['result'];

				$savepost['fees'] = $this->request->data['fees'];

				$savepost['vacancy'] = $this->request->data['vacancy'];

				$savepost['exam_pattern'] = $this->request->data['exam_pattern'];

				$savepost['cutoff'] = $this->request->data['cutoff'];

				$savepost['eligibility'] = $this->request->data['eligibility'];

				$savepost['age_limit'] = $this->request->data['age_limit'];

				$savepost['description'] = $this->request->data['exam_description'];

				if (!empty($this->request->data['postimage']['name'])) {

					$clg_logo = $file = $this->request->data['postimage'];

					$clg_logo_name = $file = $this->request->data['postimage']['name'];

					$clg_logo_path = $file = $this->request->data['postimage']['tmp_name'];

					$save_clglogo = time() . $clg_logo_name;



					$save_file = $this->uploadpostImage($save_clglogo, $clg_logo_path, 'img/Post/', '600');

					$this->request->data['image'] = $save_file;

					if (file_exists('img/Post/' . $this->request->data['saveimage']) && !empty($this->request->data['saveimage'])) {

						unlink('img/Post/' . $this->request->data['saveimage']);

					}

					$savepost['image'] = $save_file;

				}



				if (!empty($postid)) {

					$this->request->data['id'] = $postid;

				}

				$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

				$this->Tbl_posts->save($dataToSave);



				$postToSave = $this->Tbl_exam_posts->newEntity($savepost);

				$this->Tbl_exam_posts->save($postToSave);



				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				if (isset($this->request->data['Preview'])) {

					if (!empty($this->request->data['id'])) {

						$pageids = $this->request->data['id'];

					} else {

						$respage2 = $this->Tbl_posts->find("all", array('order' => array('id' => 'desc'), 'fields' => array('id')));

						$respage2->hydrate(false);

						$cmspage3 = $respage2->first();

						$pageids = $cmspage3['id'];

					}

					return $this->redirect(webURL . 'add-edit-exam-post?type=update&&pageid=' . $pageids);
					die;

				} else {

					return $this->redirect(webURL . 'exam-post-list');
					die;

				}

			}

		}

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'exam-post-list');
					die;

				}

			}

		}

	}

	public function quickpostForm()
	{

		$this->loadModel('Tbl_posts');

		$cmpid = $this->request->session()->read("company_accs.id");

		$collid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$memberid = $this->request->session()->read("Tbl_faculty_members.id");



		if ($this->request->is('post')) {

			if (!empty($cmpid)) {

				$this->request->data['company_id'] = $cmpid;

				$this->request->data['post_by'] = $cmpid;

				$this->request->data['college_id'] = '';

			} else if (!empty($collid)) {

				$this->request->data['college_id'] = $collid;

				$this->request->data['post_by'] = $memberid;

				$this->request->data['company_id'] = '';

			} else {

				$this->request->data['college_id'] = '';

				$this->request->data['company_id'] = '';

			}



			$this->request->data['title'] = 'Quick Post';

			$this->request->data['post_type'] = 'Quick Post';

			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['title'])));

			$this->request->data['created'] = date('Y-m-d h:i:s a');

			$this->request->data['updated'] = date('Y-m-d h:i:s a');

			$this->request->data['status'] = 1;



			$dataToSave = $this->Tbl_posts->newEntity($this->request->data);

			if ($this->Tbl_posts->save($dataToSave)) {

				$this->Flash->success('Post successfully saved', array('key' => 'post_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		}

	}

	public function collegeTeam()
	{



	}

	public function studentProfile()
	{



		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_student_notes');

		$this->loadModel('Tbl_student_profile_view_activities');

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$this->save_student_profile_view($id, 'Profile');

		} else {

			$id = $this->request->session()->read("Tbl_faculty_members.id");

		}





		$cmpId = $this->request->session()->read("company_accs.id");

		if (!empty($cmpId)) {



			$resactivity['student_id'] = $id;

			$resactivity['session_start_date'] = date('Y-m-d');

			$resactivity['platform'] = 'Website';

			$resactivity['session_start_time'] = date('h:i:s a');

			$resactivity['rec_id'] = $cmpId;

			$dataactivity = $this->Tbl_student_profile_view_activities->newEntity($resactivity);

			$this->Tbl_student_profile_view_activities->save($dataactivity);

		}

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$this->set('viewDetails', $datast1);



		$not = $this->Tbl_student_notes->find("all", array('conditions' => array('sid' => $id, 'cmp_id' => $cmpId)));

		$not->hydrate(false);

		$notes = $not->first();

		$this->set('notes', $notes);

	}

	public function studentnewProfile()
	{



		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$this->save_student_profile_view($id, 'Profile');

		} else {

			$id = $this->request->session()->read("Tbl_faculty_members.id");

		}

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$this->set('viewDetails', $datast1);

	}

	public function studentProfilePdf()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			// $this->save_student_profile_view($id,'Profile');

		} else {

			$id = $this->request->session()->read("Tbl_faculty_members.id");

		}

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();



		// print_r($datast1);die;

		$this->set('viewDetails', $datast1);

	}

	public function institutePost()
	{

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');



		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$uploaded_date = date('Y-m-d h:i:s a');

		$condition = array();

		if (!empty($stdId)) {



			$usersQuery1 = $this->Tbl_member_details->find('all', array('conditions' => array('acc_id' => $stdId), 'fields' => array('course', 'acc_id')));

			$usersQuery1->hydrate(false);

			$data1 = $usersQuery1->first();



			if ($profileType != 4) {

				//$condition=array('status'=>1,array('OR'=>array('college_id'=>$collegeid,'college_id'=>''),'company_id'=>0));

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id' => $collegeid)), 'updated <=' => $uploaded_date);

			} else {

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id' => $collegeid)), 'updated <=' => $uploaded_date);

			}

		} else if (!empty($cmpId)) {

			$condition = array('status' => 1, 'company_id' => $cmpId, 'updated <=' => $uploaded_date);

		} else {



			$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id' => '')), 'updated <=' => $uploaded_date);

		}



		if (isset($_GET['cat'])) {

			$this->loadModel('Wall_categorys');

			$cat = array();

			$respage = $this->Wall_categorys->find("all", array('conditions' => array('parent' => $_GET['cat'], 'status' => 1)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {
					$cat[] = $cmspages['id'];
				}

			}

			$cat[] = $_GET['cat'];

			$cat_search = array('category IN' => $cat);

			array_push($condition, $cat_search);

		}

		if (isset($_GET['type'])) {

			$type_search = array('post_type' => $_GET['type']);

			array_push($condition, $type_search);

		}

		$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

		$serser = $this->paginate('Tbl_posts');

		$this->set('blog', $serser);

	}

	public function companyPost()
	{

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');



		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$uploaded_date = date('Y-m-d h:i:s a');

		$condition = array();

		if (!empty($stdId)) {



			$usersQuery1 = $this->Tbl_member_details->find('all', array('conditions' => array('acc_id' => $stdId), 'fields' => array('course', 'acc_id')));

			$usersQuery1->hydrate(false);

			$data1 = $usersQuery1->first();





			if ($profileType != 4) {

				//$condition=array('status'=>1,array('OR'=>array('college_id'=>0,'college_id'=>''),'company_id !='=>0));

				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id' => '')), 'company_id !=' => '', 'updated <=' => $uploaded_date);

			} else {

				//$condition=array('status'=>1,array('OR'=>array('college_id'=>$collegeid,'college_id'=>'','course_id'=>$data1['course'],'course_id'=>''),'company_id !='=>0));



				$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id' => '')), 'company_id !=' => '', 'updated <=' => $uploaded_date);

			}



		} else if (!empty($cmpId)) {

			$condition = array('status' => 1, 'company_id' => $cmpId, 'updated <=' => $uploaded_date);

		} else {

			$condition = array('status' => 1, 'company_id !=' => '', array('OR' => array('college_id' => 0, 'college_id' => '')), 'updated <=' => $uploaded_date);

		}



		if (isset($_GET['cat'])) {

			$this->loadModel('Wall_categorys');

			$cat = array();

			$respage = $this->Wall_categorys->find("all", array('conditions' => array('parent' => $_GET['cat'], 'status' => 1)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {
					$cat[] = $cmspages['id'];
				}

			}

			$cat[] = $_GET['cat'];

			$cat_search = array('category IN' => $cat);

			array_push($condition, $cat_search);

		}

		if (isset($_GET['type'])) {

			$type_search = array('post_type' => $_GET['type']);

			array_push($condition, $type_search);

		}

		$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

		$serser = $this->paginate('Tbl_posts');

		$this->set('blog', $serser);



	}

	public function examShortlisted()
	{

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_post_interestes');



		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$uploaded_date = date('Y-m-d h:i:s a');

		$serser = array();

		$condition = array();



		$usersintr = $this->Tbl_post_interestes->find('all', array('conditions' => array('std_id' => $stdId), 'fields' => array('post_id')));

		$usersintr->hydrate(false);

		$dataintr = $usersintr->toArray();

		if (!empty($dataintr)) {

			$postid = array();

			foreach ($dataintr as $dataintrs) {
				$postid[] = $dataintrs['post_id'];
			}

			$postid_search = array('id IN' => $postid);



			$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))), 'updated <=' => $uploaded_date);

			array_push($condition, $postid_search);

			$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

			$serser = $this->paginate('Tbl_posts');

		}

		$this->set('blog', $serser);



	}

	public function jobApplied()
	{

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_post_applieds');



		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$cmpId = $this->request->session()->read("company_accs.id");

		$stdId = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$uploaded_date = date('Y-m-d h:i:s a');

		$serser = array();

		$condition = array();



		$usersintr = $this->Tbl_post_applieds->find('all', array('conditions' => array('std_id' => $stdId), 'fields' => array('post_id')));

		$usersintr->hydrate(false);

		$dataintr = $usersintr->toArray();

		if (!empty($dataintr)) {



			// $condition=array('status'=>1,array('OR'=>array('college_id'=>0,'college_id IN'=>array($collegeid,''))),'updated <='=>$uploaded_date);

			$condition = array('status' => 1, array('OR' => array('college_id' => 0, 'college_id IN' => array($collegeid, ''))));

			$postid = array();

			foreach ($dataintr as $dataintrs) {
				$postid[] = $dataintrs['post_id'];
			}

			$postid_search = array('id IN' => $postid);

			array_push($condition, $postid_search);



			$type_search = array('post_type IN' => array('Job', 'Internship'));

			array_push($condition, $type_search);

			$this->paginate = (array('limit' => 10, "conditions" => $condition, 'order' => array('updated' => 'desc')));

			$serser = $this->paginate('Tbl_posts');

		}

		$this->set('blog', $serser);



	}

	public function checkcourseDuplicate()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_courses');

		if ($this->request->is('ajax')) {

			$id = $this->request->data['id'];

			$stream_title = $this->request->data['stream_title'];

			$degree_awarded = $this->request->data['degree_awarded'];

			$specialization = $this->request->data['specialization'];

			$course_mode = $this->request->data['course_mode'];

			$collegesesid = $this->request->session()->read("Tbl_faculty_members.collegeid");



			if (!empty($collegesesid)) {

				if (!empty($id)) {

					$checkdata = array('stream_title' => $stream_title, 'degree_awarded' => $degree_awarded, 'specialization' => $specialization, 'course_mode' => $course_mode, 'id !=' => $id, 'college_id' => $collegesesid);



				} else {

					$checkdata = array('stream_title' => $stream_title, 'degree_awarded' => $degree_awarded, 'specialization' => $specialization, 'course_mode' => $course_mode, 'college_id' => $collegesesid);



				}

			} else {

				if (!empty($id)) {

					$checkdata = array('stream_title' => $stream_title, 'degree_awarded' => $degree_awarded, 'specialization' => $specialization, 'course_mode' => $course_mode, 'id !=' => $id);



				} else {

					$checkdata = array('stream_title' => $stream_title, 'degree_awarded' => $degree_awarded, 'specialization' => $specialization, 'course_mode' => $course_mode);



				}

			}

			$corschk = $this->Tbl_courses->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();





			if (!empty($teamcheck)) {

				echo '1';

			} else {

				echo '0';

			}

			$this->autoRender = false;

			exit();

		}

	}



	public function getstream()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_course_awardeds');

		$this->loadModel('Tbl_specializations');

		if ($this->request->is('ajax')) {

			$htmlmew = '';

			$teamcheck = '';

			$new = array();

			$checkdata = array('cat_id' => $this->request->data['stream'], 'status' => 1);

			$corschk = $this->Tbl_course_awardeds->find("all", array("conditions" => $checkdata, 'fields' => array('cat_id', 'id', 'name')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->toArray();

			foreach ($teamcheck as $teamchecks) {

				$new[] = $teamchecks['name'];

			}

			$newcourselist = array_unique($new);

			$new = array();

			$new[] = '<option data-tokens="" value="">Select Awarded</option>';

			foreach ($newcourselist as $newcourselists) {

				$new[] = '<option value="' . $newcourselists . '">' . $newcourselists . '</option>';

			}

			$htmlmew = implode(' ', $new);



			$checkdatasp = array('cat_id' => $this->request->data['stream'], 'status' => 1);

			$corschksp = $this->Tbl_specializations->find("all", array("conditions" => $checkdatasp, 'fields' => array('cat_id', 'id', 'name')));

			$corschksp->hydrate(false);

			$teamchecksp = $corschksp->toArray();



			$newsp = array();

			$newsp[] = '<option data-tokens="" value="">Select specialization</option>';

			foreach ($teamchecksp as $teamchecksps) {

				$newsp[] = '<option value="' . $teamchecksps['name'] . '">' . $teamchecksps['name'] . '</option>';

			}



			$response = array('awarded' => $htmlmew, 'specialization' => $newsp);

			echo json_encode($response);

			$this->autoRender = false;

			exit();

		}

	}

	public function getdegreeawarded()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Tbl_specializations');



		if ($this->request->is('ajax')) {

			$htmlmew = '';

			$teamcheck = '';

			$new = array();

			$checkdata = array('cat_id' => $this->request->data['stream'], 'awd_id' => $this->request->data['awarded'], 'status' => 1);

			$corschk = $this->Tbl_specializations->find("all", array("conditions" => $checkdata, 'fields' => array('cat_id', 'id', 'name')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->toArray();



			$new = array();

			$new[] = '<option data-tokens="" value="">Select specialization</option>';

			foreach ($teamcheck as $newcourselists) {

				$new[] = '<option value="' . $newcourselists['name'] . '">' . $newcourselists['name'] . '</option>';

			}

			$htmlmew = implode(' ', $new);

			$response = array('specialization' => $htmlmew);

			echo json_encode($response);

			$this->autoRender = false;

			exit();

		}

	}





	public function reviewAnswer()
	{

		$this->loadModel('Tbl_post_reviews');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		if ($this->request->is('post')) {



			$checkdata = array('post_id' => $this->request->data['pid'], 'std_id' => $membid);

			$corschk = $this->Tbl_post_reviews->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (empty($teamcheck)) {

				$rating = array();

				$poptionss = array();

				$poptions = explode(';;', $this->request->data['poptions']);



				for ($qi1 = 0; 10 > $qi1; $qi1++) {

					$rat = 'rating_' . $qi1;

					if (isset($this->request->data[$rat])) {

						$rating[] = $this->request->data[$rat];

						$poptionss[] = $poptions[$qi1];

					}

				}

				$ratings = implode(';;', $rating);

				$poptionsss = implode(';;', $poptionss);



				$this->request->data['rating'] = $ratings;

				if (!empty($membid)) {

					$this->request->data['std_id'] = $membid;

				} else {

					$this->request->data['std_id'] = 0;

				}

				$this->request->data['post_id'] = $this->request->data['pid'];

				$this->request->data['options'] = $poptionsss;

				$this->request->data['date'] = date('d-m-y H:i:s a');

				//print_r($this->request->data);die;

				$dataToSave = $this->Tbl_post_reviews->newEntity($this->request->data);

				if ($this->Tbl_post_reviews->save($dataToSave)) {

					//$this->Flash->success('Data successfully update',array('key'=>'acc_alert'));

					return $this->redirect($this->request->data['pgurl']);
					die;

				}

			} else {

				return $this->redirect($this->request->data['pgurl']);
				die;

			}

		}

	}

	public function quizAnswer()
	{

		$this->loadModel('Tbl_post_quizs');

		$this->loadModel('Tbl_student_job_activities');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		if ($this->request->is('post')) {

			$checkdata = array('post_id' => $this->request->data['pid'], 'std_id' => $membid);

			$corschk = $this->Tbl_post_quizs->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (!empty($teamcheck)) {



				$this->request->data['id'] = $teamcheck['id'];

			}

			$answer1 = $this->request->data['answer'];

			if (isset($this->request->data['right_answer'])) {

				$right_answer = implode(';;', $this->request->data['right_answer']);

				$reg = $this->request->data['right_answer'];

				foreach ($reg as $regs) {

					$answers[] = $answer1[$regs];

				}

				$answer = implode(';;', $answers);

				$this->request->data['answer'] = $right_answer;

			}

			if (!empty($membid)) {

				$this->request->data['std_id'] = $membid;

			} else {

				$this->request->data['std_id'] = 0;

			}



			$this->request->data['post_id'] = $this->request->data['pid'];

			$this->request->data['options'] = $answer;

			$this->request->data['date'] = date('d-m-y H:i:s a');



			$dataToSave = $this->Tbl_post_quizs->newEntity($this->request->data);

			if ($this->Tbl_post_quizs->save($dataToSave)) {

				$resactivity['student_id'] = $membid;

				$resactivity['session_start_date'] = date('Y-m-d');

				$resactivity['platform'] = 'Website';

				$resactivity['session_start_time'] = date('h:i:s a');

				$resactivity['job_id'] = $this->request->data['pid'];

				$resactivity['job_activity'] = 'Quiz Answer';

				$dataactivity = $this->Tbl_student_job_activities->newEntity($resactivity);

				$this->Tbl_student_job_activities->save($dataactivity);



				//$this->Flash->success('Data successfully update',array('key'=>'acc_alert'));

				return $this->redirect($this->request->data['pgurl']);
				die;

			}

		}

	}



	public function surveyAnswer()
	{

		$this->loadModel('Tbl_survey_post_answers');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		if ($this->request->is('post')) {

			$checkdata = array('post_id' => $this->request->data['pid'], 'std_id' => $membid);

			$corschk = $this->Tbl_survey_post_answers->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

			$corschk->hydrate(false);

			$teamcheck = $corschk->first();

			if (!empty($teamcheck)) {



				$this->request->data['id'] = $teamcheck['id'];

			}

			$answer1 = $this->request->data['answer'];

			if (isset($this->request->data['right_answer'])) {

				$right_answer = implode(';;', $this->request->data['right_answer']);

				$reg = $this->request->data['right_answer'];

				foreach ($reg as $regs) {

					$answers[] = $answer1[$regs];

				}

				$answer = implode(';;', $answers);

				$this->request->data['answer'] = $right_answer;

			}

			if (!empty($membid)) {

				$this->request->data['std_id'] = $membid;

			} else {

				$this->request->data['std_id'] = 0;

			}



			$this->request->data['post_id'] = $this->request->data['pid'];

			$this->request->data['options'] = $answer;

			$this->request->data['date'] = date('d-m-y H:i:s a');



			$dataToSave = $this->Tbl_survey_post_answers->newEntity($this->request->data);

			if ($this->Tbl_survey_post_answers->save($dataToSave)) {

				return $this->redirect($this->request->data['pgurl']);
				die;

			}

		}

	}

	public function personalityAnswer()
	{

		$this->loadModel('Tbl_personality_post_answers');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		if ($this->request->is('post')) {

			if (!empty($membid)) {

				$this->request->data['std_id'] = $membid;

			} else {

				$this->request->data['std_id'] = 0;

			}



			$this->request->data['post_id'] = $this->request->data['pid'];

			$this->request->data['answer'] = $this->request->data['right_answer'];

			$this->request->data['date'] = date('d-m-y H:i:s a');



			$dataToSave = $this->Tbl_personality_post_answers->newEntity($this->request->data);

			if ($this->Tbl_personality_post_answers->save($dataToSave)) {

				return $this->redirect($this->request->data['pgurl']);
				die;

			}

		}

	}







	public function appliedList($post_id = 0)
	{

		$this->loadModel('Tbl_post_applieds');

		$this->loadModel('Tbl_courses');

		$conn = ConnectionManager::get('default');



		//searh query.............	



		$level = '';

		$cors_ids = array();
		$std_ids = array();
		$results_course = array();

		if (isset($_GET['course']) && !empty($_GET['course']) && isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  AND tbl_courses.level ="' . $_GET['level'] . '" where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['level']) && !empty($_GET['level'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_courses where level="' . $_GET['level'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');



		} else if (isset($_GET['course']) && !empty($_GET['course'])) {

			$stmt_course = $conn->execute('select tbl_courses.id from tbl_specializations INNER JOIN  tbl_courses ON tbl_courses.specialization = tbl_specializations.name  where tbl_specializations.main_cat="' . $_GET['course'] . '" OR tbl_courses.stream_title = "' . $_GET['course'] . '"');

			$results_course = $stmt_course->fetchAll('assoc');

		}

		$type_search = '';

		if (isset($_GET['course'])) {

			$std_ids = '';

			if (!empty($results_course)) {

				foreach ($results_course as $results_courses) {

					$cors_ids[] = $results_courses['id'];

				}

			}

			if (empty($cors_ids) && !empty($_GET['course'])) {

				$std_ids = "";

			} else {

				$std_ids = $this->_student_details($cors_ids);

			}

			if (!empty($std_ids)) {

				$stdnewids = implode(',', $std_ids);

				$type_search = ' AND tbl_faculty_members.id IN (' . $stdnewids . ')';

			} else {

				$type_search = ' AND tbl_faculty_members.id=000';

			}





		}

		$loc_search = '';

		if (isset($_GET['loc']) && !empty($_GET['loc'])) {

			$tysloc = explode(',', $_GET['loc']);

			$tyloc = array();

			if (!empty($tysloc)) {

				foreach ($tysloc as $tyslocs) {

					if (!empty($tyslocs)) {

						$tyloc[] = '"' . $tyslocs . '"';

					}

				}

			}

			if (!empty($tyloc)) {



				$newtyloc = implode(',', $tyloc);

				$loc_search = 'AND (tbl_faculty_members.state IN (' . $newtyloc . ') OR tbl_faculty_members.district IN (' . $newtyloc . ')  OR tbl_faculty_members.city IN (' . $newtyloc . ') )';



			}

		}

		$profile_search = '';

		if (isset($_GET['profile']) && !empty($_GET['profile'])) {

			//$acc_search = array('acc_verify'=>$_GET['profile']);

			$profile_search = ' AND tbl_faculty_members.acc_verify =' . $_GET['profile'] . '';

		}



		//......................



		$stmt = $conn->execute('select tbl_faculty_members.id,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_faculty_members.acc_verify,tbl_courses.name as course_name,tbl_courses.level,tbl_schools.name as college_name,tbl_post_applieds.date,tbl_member_details.year,tbl_member_details.is_alumini,tbl_member_details.12_aggeregate,tbl_member_details.pg_aggeregate,tbl_member_details.edu_percentage,tbl_member_details.course,tbl_student_shortlisted_profiles.profile_name from tbl_post_applieds 

		LEFT JOIN  tbl_faculty_members ON tbl_post_applieds.std_id = tbl_faculty_members.id  

		LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id 

		LEFT JOIN  tbl_student_shortlisted_profiles ON tbl_student_shortlisted_profiles.sid = tbl_faculty_members.id

		LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_post_applieds.post_id =' . $post_id . ' ' . $type_search . ' ' . $loc_search . ' ' . $profile_search . ' group by tbl_post_applieds.std_id order by tbl_post_applieds.id desc');



		//	print_r($stmt);die;

		$datast = $stmt->fetchAll('assoc');



		$this->set('quizlist', $datast);

		$this->set('post_id', $post_id);

	}

	public function interestedList($post_id = 0)
	{

		$this->loadModel('Tbl_post_interestes');



		$restd = $this->Tbl_post_interestes->find("all", array('conditions' => array('post_id' => $post_id)), array('order' => array('id' => 'DESC')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('quizlist', $datast);

		$this->set('post_id', $post_id);

	}

	public function quizList($post_id = 0)
	{

		$this->loadModel('Tbl_post_quizs');



		$restd = $this->Tbl_post_quizs->find("all", array('conditions' => array('post_id' => $post_id)), array('order' => array('id' => 'DESC')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('quizlist', $datast);

		$this->set('post_id', $post_id);

	}

	public function reviewList($post_id = 0)
	{

		$this->loadModel('Tbl_post_reviews');



		$restd = $this->Tbl_post_reviews->find("all", array('conditions' => array('post_id' => $post_id)), array('order' => array('id' => 'DESC')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('quizlist', $datast);

		$this->set('post_id', $post_id);

	}

	/* public function postInterestedList($post_id=0)

							{  		

							   $this->loadModel('Tbl_post_interestes');

								  $restd=$this->Tbl_post_interestes->find("all",array('conditions'=>array('post_id'=>$post_id)));

								  $restd->hydrate(false);

								  $datast=$restd->toArray();

								  $this->set('list',$datast); 

								  $this->set('post_id',$post_id);

							}



						  public function postapplyList($post_id=0)

							{  		

							   $this->loadModel('Tbl_post_applieds');



								  $restd=$this->Tbl_post_applieds->find("all",array('conditions'=>array('post_id'=>$post_id)));

								  $restd->hydrate(false);

								  $datast=$restd->toArray();

								  $this->set('list',$datast);

								  $this->set('post_id',$post_id);

							}*/

	public function quizpostList()
	{

		$this->loadModel('Tbl_posts');

		$restd = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => 'Q&A'), 'order' => array('updated' => 'desc')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('postlist', $datast);

	}

	public function reviewpostList()
	{

		$this->loadModel('Tbl_posts');



		$restd = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => 'Review'), 'order' => array('updated' => 'desc')));

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('postlist', $datast);



	}

	/* lifeset wall ............. */

	public function coursecategoryList()
	{

		$this->loadModel('Tbl_course_categorys');

		$respage = '';

		$respage = $this->Tbl_course_categorys->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('result', $cmspage);

	}

	public function addeditcourseCategory()
	{

		$this->loadModel('Tbl_course_categorys');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Tbl_course_categorys->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('result', $cmspage);



		if ($this->request->is('post')) {

			$this->request->data['date'] = date('Y-m-d h:i:s a');

			$this->request->data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->data['name'])));



			$dataToSave = $this->Tbl_course_categorys->newEntity($this->request->data);

			if ($this->Tbl_course_categorys->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'course-category-list');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Tbl_course_categorys->get($pageid);

			if ($this->Tbl_course_categorys->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'course-category-list');
				die;

			}

		}

	}

	public function awardedList()
	{

		$this->loadModel('Tbl_course_awardeds');

		$respage = '';

		$respage = $this->Tbl_course_awardeds->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('result', $cmspage);

	}

	public function addeditawarded()
	{

		$this->loadModel('Tbl_course_awardeds');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Tbl_course_awardeds->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('result', $cmspage);



		if ($this->request->is('post')) {



			$dataToSave = $this->Tbl_course_awardeds->newEntity($this->request->data);

			if ($this->Tbl_course_awardeds->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'awarded-list');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Tbl_course_awardeds->get($pageid);

			if ($this->Tbl_course_awardeds->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'awarded-list');
				die;

			}

		}

	}

	public function specializationList()
	{
		$this->loadModel('Tbl_specializations');

		$respage = $this->Tbl_specializations->find("all", array(
			'order' => array('name' => 'ASC')  // Alphabetical sorting
		));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('result', $cmspage);
	}


	public function addeditspecialization()
	{

		$this->loadModel('Tbl_specializations');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Tbl_specializations->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('result', $cmspage);



		if ($this->request->is('post')) {



			$dataToSave = $this->Tbl_specializations->newEntity($this->request->data);

			if ($this->Tbl_specializations->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'specialization-list');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Tbl_specializations->get($pageid);

			if ($this->Tbl_specializations->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'specialization-list');
				die;

			}

		}

	}

	public function wallcategoryList()
	{

		$this->loadModel('Wall_categorys');

		$respage = '';

		$respage = $this->Wall_categorys->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();
		// echo "<pre>";
		// print_r($respage);
		// echo "</pre>";
		// exit;

		$this->set('result', $cmspage);

	}


	public function postCat()
	{

		$this->loadModel('PostCat');

		$respage = '';

		$respage = $this->PostCat->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();
		echo "<pre>";
		print_r($cmspage);
		echo "</pre>";
		exit;

		$this->set('result', $cmspage);

	}
	public function postGk()
	{

		$this->loadModel('PostGk');

		$respage = '';

		$respage = $this->PostGk->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();
		// echo "<pre>";
		// print_r($cmspage);
		// echo "</pre>";
		// exit;

		$this->set('result', $cmspage);

	}
	public function addeditwallCategory()
	{

		$this->loadModel('Wall_categorys');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Wall_categorys->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('result', $cmspage);



		if ($this->request->is('post')) {



			$dataToSave = $this->Wall_categorys->newEntity($this->request->data);

			if ($this->Wall_categorys->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'wall-category-list');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Wall_categorys->get($pageid);

			if ($this->Wall_categorys->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'wall-category-list');
				die;

			}

		}

	}
	// Controller: PostCategoriesController.php
	public function addeditpostCategory($id = null)
{
    $this->loadModel('PostGkCat');      // parent category table
    $this->loadModel('PostGkSubcat');   // sub category table
    $this->loadModel('PostGkSection');   // section table (final table)

    // ---- STEP 1: Edit Mode (if id is passed) ----
    $result = null;
    if ($id) {
        $result = $this->PostGkSection->find()
            ->where(['PostGkSection.id' => $id])
            ->first();
    }
    $this->set('result', $result);

    // ---- STEP 2: Save Form ----
    if ($this->request->is(['post', 'put'])) {
        $data = $this->request->getData();

        // ✅ CASE 1: Add/Edit Sub Category Only
        if (!empty($data['sub_category_name'])) {
            $subcatEntity = !empty($data['sub_category_id'])
                ? $this->PostGkSubcat->get($data['sub_category_id'])
                : $this->PostGkSubcat->newEntity();

            $subcatEntity = $this->PostGkSubcat->patchEntity($subcatEntity, [
                'category_id' => $data['category_id'],
                'sub_category_name' => $data['sub_category_name'],
            ]);
            $this->PostGkSubcat->save($subcatEntity);

            $this->Flash->success('Sub Category saved successfully.');
            return $this->redirect(['action' => 'addeditPostGkSection']);
        }

        // ✅ CASE 2: Add/Edit Post Category (section)
        if (!empty($data['section'])) {
            $entity = !empty($data['id'])
                ? $this->PostGkSection->get($data['id'])
                : $this->PostGkSection->newEntity();

            $entity = $this->PostGkSection->patchEntity($entity, [
                'category_id' => $data['category_id'] ?? null,
                'sub_category_id' => $data['sub_category_id'] ?? null,
                'section_name' => $data['section'] ?? null,
                'status' => 1
            ]);

            if ($this->PostGkSection->save($entity)) {
                $this->Flash->success('Post Category (Section) saved successfully.');
             return $this->redirect(webURL . 'post-category');
            } else {
                $this->Flash->error('Failed to save Post Category.');
            }
        }
    }

    // ---- STEP 3: Dropdown values ----
    $categories = $this->PostGkCat->find('list', [
        'keyField' => 'id',
        'valueField' => 'category_name'
    ])->toArray();

    $this->set(compact('categories'));
}


	// Ajax: Sub Categories fetch
	public function getSubCategories()
	{
		$this->autoRender = false;
		$this->loadModel('PostGkSubcat');

		$categoryId = $this->request->getQuery('category_id');
		$subCategories = $this->PostGkSubcat->find('list', [
			'keyField' => 'id',
			'valueField' => 'sub_category_name'
		])->where(['category_id' => $categoryId])->toArray();

		foreach ($subCategories as $id => $name) {
			echo "<option value='{$id}'>{$name}</option>";
		}
		exit;
	}

	public function addeditsubCategory()
	{
		$this->loadModel('PostGkSubcat'); // ✅ sub_categories table model load

		$pageid = $this->request->getQuery('id') ?? '';
		$type = $this->request->getQuery('type') ?? '';

		// Edit data
		$respage = $this->PostGkSubcat->find("all", [
			'conditions' => ['id' => $pageid]
		])->enableHydration(false)->first();
		$this->set('result', $respage);

		// Save / Update
		if ($this->request->is('post')) {
			$data = $this->request->getData();

			if (!empty($data['id'])) {
				$entity = $this->PostGkSubcat->get($data['id']);
				$this->PostGkSubcat->patchEntity($entity, $data);
			} else {
				$entity = $this->PostGkSubcat->newEntity($data);
			}

			if ($this->PostGkSubcat->save($entity)) {
				$this->Flash->success('Sub Category successfully saved', ['key' => 'acc_alert']);
				return $this->redirect(webURL . 'add-edit-post-category');
			} else {
				$this->Flash->error('Something went wrong, please try again.', ['key' => 'acc_alert']);
			}
		}

		// Delete
		if ($type == "delete") {
			$content = $this->PostGkSubcat->get($pageid);
			if ($this->PostGkSubcat->delete($content)) {
				$this->Flash->success('Sub Category successfully deleted', ['key' => 'acc_alert']);
				return $this->redirect(webURL . 'add-edit-post-category');
			}
		}
	}




	public function addeditpostCat()
	{

		$this->loadModel('Post_cat');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Post_cat->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('result', $cmspage);



		if ($this->request->is('post')) {



			$dataToSave = $this->Post_cat->newEntity($this->request->data);

			if ($this->Post_cat->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'post-cat');
				die;

			}

		}

		if ($type == "delete") {

			$content = $this->Post_cat->get($pageid);

			if ($this->Post_cat->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'post-cat');
				die;

			}

		}

	}

	public function wallbulkUpload()
	{

		$this->loadModel('Tbl_posts');



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('image !=' => ''), 'fields' => array('id', 'image', 'updated')), array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('result', $cmspage);



		if ($type == "delete") {

			$content = $this->Tbl_posts->get($pageid);

			if ($this->Tbl_posts->delete($content)) {

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'wall-category-list');
				die;

			}

		}

	}

	public function uploadwallCsv()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Wall_categorys');



		if ($this->request->is('post')) {



			$filename = $this->request->data['csv_file']['name'];

			$ext = pathinfo($filename, PATHINFO_EXTENSION);





			if ($ext == 'csv') {

				$file = $this->request->data['csv_file']['tmp_name'];

				$csvFile = file($file);



				//$filesop = [];



				$c = 0;

				foreach ($csvFile as &$line) {



					if ($c != 0) {

						$filesop = str_getcsv($line);

						$dataSave = array();

						$search = array();

						//print_r($filesop[4]);die;

						if (!empty($filesop[0])) {

							/*

																																																$rescmp =$this->Tbl_company_accs->find("all",array('conditions'=>array('name'=>$filesop[0]),'fields'=>array('id')));	

																																																$rescmp->hydrate(false);

																																																$cmscmp =  $rescmp->first();

																																																if(!empty($cmscmp)){

																																																   $dataSave['company_id'] =$cmscmp['id'];

																																																   $search['company_id']=$cmscmp['id'];

																																																}

																																															 */

							if ($filesop[0] == 'Review') {



								if (!empty($filesop[0])) {
									$search['post_type'] = $filesop[0];
								}

								if (!empty($filesop[1])) {
									$search['question'] = $filesop[1];
								}

								$dataSave['post_type'] = $filesop[0];

								$dataSave['question'] = $filesop[1];

								if (!empty($filesop[2])) {

									$options = explode(',', $filesop[2]);

									$dataSave['options'] = implode(';;', $options);

								}



								$originalDate = $filesop[3] . ' ' . $filesop[4];

								$dataSave['updated'] = date("Y-m-d h:i:s a", strtotime($originalDate));

								//$dataSave['updated']=date('Y-m-d h:i:s a');

								$dataSave['status'] = 1;



								$respage = $this->Tbl_posts->find("all", array('conditions' => $search, 'fields' => array('id')));

								$respage->hydrate(false);

								$cmspage = $respage->first();

								if (!empty($cmspage)) {

									$dataSave['id'] = $cmspage['id'];

								} else {

									$dataSave['created'] = date('Y-m-d h:i:s a');

								}

								$dataSave['image'] = $filesop[5];



							} else if ($filesop[0] == 'Q&A') {



								if (!empty($filesop[1])) {
									$search['post_type'] = $filesop[0];
								}

								if (!empty($filesop[2])) {
									$catsearch = $filesop[2];
								} else if (!empty($filesop[1])) {
									$catsearch = $filesop[1];
								} else {
									$catsearch = '';
								}



								$dataSave['category'] = $catsearch;



								if (!empty($filesop[3])) {
									$search['objquestion'] = $filesop[3];
								}

								$dataSave['post_type'] = $filesop[0];

								$dataSave['objquestion'] = $filesop[3];

								if (!empty($filesop[4])) {

									$options = explode(',', $filesop[4]);

									$rightanswer = explode(',', $filesop[5]);

									$rganswer = array();

									$newanswer = array();

									//$dataSave['answer'] = implode(';;',$options);

									for ($oj = 1; $oj <= count($options); $oj++) {

										$osj = $oj - 1;

										if (in_array($oj, $rightanswer)) {
											$rganswer[] = 1;
										} else {
											$rganswer[] = 0;
										}

										$newanswer[] = $options[$osj];

									}

									$dataSave['right_answer'] = implode(';;', $rganswer);

									$dataSave['answer'] = implode(';;', $newanswer);

								}

								$originalDate = $filesop[6] . ' ' . $filesop[7];

								$dataSave['updated'] = date("Y-m-d h:i:s a", strtotime($originalDate));

								$dataSave['status'] = 1;





								$respage = $this->Tbl_posts->find("all", array('conditions' => $search, 'fields' => array('id')));

								$respage->hydrate(false);

								$cmspage = $respage->first();

								if (!empty($cmspage)) {

									$dataSave['id'] = $cmspage['id'];

								} else {

									$dataSave['created'] = date('Y-m-d h:i:s a');

								}

								$dataSave['image'] = $filesop[8];



								//print_r($dataSave['answer']);die;



							} else if ($filesop[0] == 'Job' || $filesop[0] == 'Internship') {

								if (!empty($filesop[0])) {
									$search['post_type'] = $filesop[0];
								}

								if (!empty($filesop[1])) {
									$search['title'] = $filesop[1];
								}

								$dataSave['post_type'] = $filesop[0];

								$dataSave['title'] = $filesop[1];

								$dataSave['description'] = $filesop[2];

								$dataSave['job_type'] = $filesop[3];

								$dataSave['industry'] = $filesop[4];

								$dataSave['function'] = $filesop[5];

								$dataSave['role'] = $filesop[6];

								$dataSave['past_experience'] = $filesop[7];

								$dataSave['job_location'] = $filesop[8];

								$dataSave['skill'] = $filesop[9];

								$dataSave['client_to_manage'] = $filesop[10];

								$dataSave['capacity'] = $filesop[11];

								$dataSave['variable_sallery'] = $filesop[12];

								$dataSave['fixed_salary'] = $filesop[13];

								$dataSave['working_days'] = $filesop[14];

								$dataSave['work_time'] = $filesop[15];

								$originalDate = $filesop[16] . ' ' . $filesop[17];



								$dataSave['updated'] = date("Y-m-d h:i:s a", strtotime($originalDate));



								$dataSave['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $filesop[1])));



								$dataSave['status'] = 1;



								$respage = $this->Tbl_posts->find("all", array('conditions' => $search, 'fields' => array('id')));

								$respage->hydrate(false);

								$cmspage = $respage->first();



								if (!empty($cmspage)) {

									$dataSave['id'] = $cmspage['id'];

								} else {

									$dataSave['created'] = date('Y-m-d h:i:s a');

								}

								$dataSave['image'] = $filesop[18];



								unset($dataSave['category']);

							} else {

								if (!empty($filesop[0])) {
									$search['post_type'] = $filesop[0];
								}

								if (!empty($filesop[1])) {
									$search['title'] = $filesop[1];
								}

								$dataSave['post_type'] = $filesop[0];

								$dataSave['title'] = $filesop[1];

								$dataSave['description'] = $filesop[2];

								$dataSave['post_url'] = $filesop[3];

								$dataSave['pincode'] = $filesop[4];

								$dataSave['state'] = $filesop[5];

								$dataSave['district'] = $filesop[6];

								$dataSave['hobbies'] = $filesop[7];

								//$dataSave['uploaded_date'] = $filesop[9];

								//$dataSave['uploaded_time'] = $filesop[10];



								$dataSave['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $filesop[1])));





								$originalDate = $filesop[8] . ' ' . $filesop[9];

								$dataSave['updated'] = date("Y-m-d h:i:s a", strtotime($originalDate));

								$dataSave['status'] = 1;



								$respage = $this->Tbl_posts->find("all", array('conditions' => $search, 'fields' => array('id')));

								$respage->hydrate(false);

								$cmspage = $respage->first();

								if (!empty($cmspage)) {

									$dataSave['id'] = $cmspage['id'];

								} else {

									$dataSave['created'] = date('Y-m-d h:i:s a');

								}

								$dataSave['image'] = $filesop[10];

							}

							// print_r($dataSave);die;

							$dataToSave = $this->Tbl_posts->newEntity($dataSave);

							$this->Tbl_posts->save($dataToSave);

						}

					}

					$c = $c + 1;

				}





				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

			} else {

				$this->Flash->success('Only csv file allowed', array('key' => 'acc_alert'));

			}

			return $this->redirect(webURL . 'wall-bulk-upload');
			die;

		}

	}



	public function uploadwallimageCsv()
	{

		$this->loadModel('Tbl_posts');

		/*if($this->request->is('post'))

														 { 

															 //$images = array();

															 $filename = $this->request->data['image_zip']['name'];

															 //$imagescount =count($this->request->data['image_zip']);

														 if(!empty($filename))

															 { 

																 $source = $this->request->data['image_zip']["tmp_name"];

																 $type = $this->request->data['image_zip']["type"];

																 $name = explode(".", $filename);

																 $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');

																 foreach($accepted_types as $mime_type) 

																 {

																	 if($mime_type == $type) {

																		 $okay = true;

																		 break;

																	 } 

																 }

																 $continue = strtolower($name[1]) == 'zip' ? true : false;

																 if(!$continue) {

																	$this->Flash->success('The file you are trying to upload is not a .zip file. Please try again.',array('key'=>'acc_alert'));

																	return $this->redirect(webURL.'wall-bulk-upload');die;



																 }else{



																   $path='img/Post/';

																   $new_path='img/Post/';

																   $filenoext = basename ($filename, '.zip');  

																   $filenoext = basename ($filenoext, '.ZIP');  

																   $targetdir = $path . $filenoext; 

																   $targetzip = $path . $filename;

																  // mkdir($targetdir, 0777);

																 if(move_uploaded_file($source, $targetzip)) {

																		 $zip = new ZipArchive();

																		 $x = $zip->open($targetzip); 

																		 if ($x === true) {

																			 $zip->extractTo($targetdir); 

																			 //rename($targetdir, $new_path);

																			 $zip->close();

																			 unlink($targetzip);

																		 }

																 }

																  $this->Flash->success('Images successfully uploaded',array('key'=>'acc_alert'));

																  return $this->redirect(webURL.'wall-bulk-upload');die;

															   }

															}

														 }

													   */

		if ($this->request->is('post')) {



			$images = array();

			$images = $this->request->data['image_file'][0]['name'];

			$imagescount = count($this->request->data['image_file']);

			if (!empty($images)) {

				for ($i = 0; $i < $imagescount; $i++) {

					$gallery_image_name = $file = $this->request->data['image_file'][$i]['name'];

					$gallery_image_tmp_name = $file = $this->request->data['image_file'][$i]['tmp_name'];

					if (!empty($gallery_image_name)) {   //$NewImageName1 = time().preg_replace('/\s+/', '_', $gallery_image_name);

						@move_uploaded_file($gallery_image_tmp_name, WWW_ROOT . 'img/Post/' . $gallery_image_name);

					}

				}

			}

			$this->Flash->success('Images successfully uploaded', array('key' => 'acc_alert'));

			return $this->redirect(webURL . 'wall-bulk-upload');
			die;

		}



	}

	public function getwallcategoryList()
	{

		$this->viewBuilder()->layout('');

		$this->loadModel('Wall_categorys');



		$Html = '<option value="0" >Self</option>';

		if (isset($_GET['cat'])) {



			if ($_GET['cat'] != 'Q') {
				$cat = $_GET['cat'];
			} else {
				$cat = 'Q&A';
			}



			$respage = $this->Wall_categorys->find("all", array('conditions' => array('cat_id' => $cat, 'parent' => 0)));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();



			if (!empty($cmspage)) {

				foreach ($cmspage as $cmspages) {

					$Html .= '<option value="' . $cmspages['id'] . '" >' . $cmspages['name'] . '</option>';

				}

			}

		}

		print_r($Html);
		die;

	}
	public function getpostcategoryList()
	{
		$this->viewBuilder()->layout(''); // CakePHP 2.x
		$this->loadModel('ParentCategory');

		$Html = '<option value="">-- Select Category --</option>';

		$respage = $this->ParentCategory->find()
			->select(['id', 'category'])
			->all();




		foreach ($respage as $cmspages) {
			$Html .= '<option value="' . $cmspages['id'] . '">' . $cmspages['category'] . '</option>';
		}

		echo $Html;
		die;
	}
	public function getcategoryList()
	{
		$this->viewBuilder()->layout(''); // CakePHP 2.x
		$this->loadModel('PostGkCat');

		$Html = '<option value="">-- Select Category --</option>';

		$respage = $this->PostGkCat->find()
			->select(['id', 'category_name'])
			->all();



		foreach ($respage as $cmspages) {
			$Html .= '<option value="' . $cmspages['id'] . '">' . $cmspages['category_name'] . '</option>';
		}

		echo $Html;
		die;
	}



	public function subCategory()
	{
		$this->viewBuilder()->layout(''); // CakePHP 2.x
		$this->loadModel('PostGkSubcat');

		$Html = '<option value="">-- Select Category --</option>';

		$respage = $this->PostGkSubcat->find()
			->select(['id', 'sub_category_name'])
			->all();



		echo "<pre>";
		print_r($respage);
		echo "</pre>";
		exit;
		foreach ($respage as $cmspages) {
			$Html .= '<option value="' . $cmspages['id'] . '">' . $cmspages['sub_category_name'] . '</option>';
		}

		echo $Html;
		die;
	}
	public function getpostCategory()
	{
	
		$this->loadModel('Wall_categorys');

		$respage = '';

		$respage = $this->Wall_categorys->find("all", array('order' => array('id' => 'DESC')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();
		echo "<pre>";
		print_r($respage);
		echo "</pre>";
		exit;

		$this->set('result', $cmspage);

	}



	public function add()
	{
		$this->loadModel('PostCat');

		// Distinct categories fetch karo
		$categories = $this->PostCat->find()
			->select(['category'])
			->distinct(['category'])
			->order(['category' => 'ASC'])
			->enableHydration(false)
			->toArray();

		$this->set(compact('categories'));
	}


	public function studentrequestList()
	{

		$this->loadModel('Tbl_faculty_members');

		$profileType = $this->request->session()->read("Tbl_faculty_members.profileType");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($profileType) && $profileType == 1) {



			$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'new_request' => 1, 'college_id' => $collegeid)), array('order' => array('id' => 'DESC')));



		} else {

			$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'new_request' => 1)), array('order' => array('id' => 'DESC')));

		}

		$restd->hydrate(false);

		$datast = $restd->toArray();

		$this->set('viewData', $datast);

		$membid = $this->request->session()->read("Tbl_faculty_members.id");



		if (isset($_GET['upid']) && !empty($_GET['upid'])) {

			$upid = $_GET['upid'];

			$datacsLog = array('id' => $upid, 'status' => 1, 'new_request' => 0);

			$datacsSave = $this->Tbl_faculty_members->newEntity($datacsLog);

			$this->Tbl_faculty_members->save($datacsSave);



			$cmp = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $upid), 'fields' => array('name', 'mobile', 'acc_password', 'email')));

			$cmp->hydrate(false);

			$cmpdata = $cmp->first();



			$this->accconfirom($cmpdata['mobile'], base64_decode($cmpdata['acc_password']));



			$to = $cmpdata['email'];

			$subject = "Student Account Activated Successfully On LifeSet";

			$headers = "MIME-Version: 1.0\r\n";

			$headers .= 'From: info@lifeset.co.in' . "\r\n";

			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";





			$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>	A Student Networking Site from Bharat</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $cmpdata['name'] . ",</h2>

                              <h2 style='font-size:30px; font-weight:normal;'>Account Login Details </h2>

                             

                              

                                    <!-- Content Section start here ------------------- -->

                              <p style='font-size:26px; font-weight:normal;'>Here you can build your own Network from the vast student community. Interact, learn, create your profile and explore internship/career opportunities.</p>

                              <p style='font-size:26px; font-weight:normal;'>We are verifying your account details and the Institute information that you have provided us with and shall inform you of any required change via email/call.</p>

                              

                              <p style='padding:20px 50px; font-size:18px; line-height:23px font-weight:bold;'>Student Details</p>

                              <p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Welcome to LifeSet. Your account has been successfully activated. Your Login ID " . webMobile . " and Password " . base64_decode($cmpdata['acc_password']) . ".  </h3></p>

                        		<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>	Install our application by clicking on the logo or you can login in https://lifeset.co.in/login </h3></p>

                        			

                        		<a href='https://play.google.com/store/apps/details?id=com.lifeset.team' ><img src='https://lifeset.co.in/theme/images/lifeset_playstore.png' alt='Lifeset App on Playstore'></a>		

                        	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in' />

                              

                              </td>

                          </tr>

                          <tr style='background:#ededed; color:#000;'>

                            <td align='center' style='padding:30px'>

                            

                                        <!-- Action Button Section start here ------------------- -->

                            <a href='https://lifeset.co.in/login' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>Login</a></td>

                          </tr>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";

			mail($to, $subject, $body, $headers);

			$this->Flash->success('Account successfully activated', array('key' => 'acc_alert', ));

			return $this->redirect(webURL . 'student-request-list');
			die;

		}

		if (isset($_GET['dlid'])) {

			$cmp = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $_GET['dlid']), 'fields' => array('mobile', 'email')));

			$cmp->hydrate(false);

			$datamemchdtl = $cmp->first();



			$content = $this->Tbl_faculty_members->get($_GET['dlid']);

			if ($this->Tbl_faculty_members->delete($content)) {



				$this->remove_temp_by_mobile($datamemchdtl['mobile']);

				$this->remove_temp_by_email($datamemchdtl['email']);



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert', ));

				return $this->redirect(webURL . 'student-request-list');
				die;

			}

		}

	}



	public function setprofileStaus()
	{

		$this->loadModel('Tbl_faculty_members');



		if ($this->request->is('post')) {

			$dataToSave = $this->Tbl_faculty_members->newEntity($this->request->data);

			if ($this->Tbl_faculty_members->save($dataToSave)) {

				// $this->Flash->success('Data successfully saved',array('key'=>'acc_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		}

	}

	public function scoreResults()
	{



	}



	public function recruitershortlistedList()
	{

		$company_id = $this->request->session()->read("company_accs.id");



		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_shortlisted_profiles.sid,tbl_student_shortlisted_profiles.pro_status,tbl_student_shortlisted_profiles.profile_name,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_courses.name as course_name,tbl_schools.name as college_name from tbl_student_shortlisted_profiles LEFT JOIN  tbl_faculty_members ON tbl_student_shortlisted_profiles.sid = tbl_faculty_members.id  LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_student_shortlisted_profiles.cmp_id =' . $company_id . ' AND tbl_faculty_members.status=1');

		$result = $stmt->fetchAll('assoc');



		$this->set('viewData', $result);

	}

	public function recruiteractivityList()
	{



		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_shortlisted_profiles.sid,tbl_student_shortlisted_profiles.pro_status,tbl_student_shortlisted_profiles.profile_name,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_courses.name as course_name,tbl_company_accs.name as recruite_name from tbl_student_shortlisted_profiles LEFT JOIN  tbl_faculty_members ON tbl_student_shortlisted_profiles.sid = tbl_faculty_members.id  LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id LEFT JOIN  tbl_company_accs ON tbl_company_accs.id = tbl_student_shortlisted_profiles.cmp_id where tbl_faculty_members.college_id =' . $collegeid . ' AND tbl_faculty_members.status=1');

		$result = $stmt->fetchAll('assoc');



		$this->set('viewData', $result);

	}



	public function recruiteractivitysummaryList()
	{



	}

	public function recruiteractivitystreamList()
	{





	}

	public function recruiteractivitystreamyesrList()
	{





	}





	public function logout()
	{

		$session = $this->request->session();

		$session->delete('Tbl_faculty_members.collegeid');

		$session->delete('Tbl_faculty_members.id');



		$google_token = $this->request->session()->read("Tbl_faculty_members.google_id");

		$this->request->session()->destroy();

		if (!empty($google_token)) {

			$session->delete('Tbl_faculty_members.google_id');

			return $this->redirect(webURL . "google/logout.php");
			die;

		}

		return $this->redirect(webURL . "login");
		die;

		die;

	}



	public function cmpLogout()
	{

		$session = $this->request->session();

		$session->delete('Tbl_faculty_members.collegeid');

		$session->delete('Tbl_faculty_members.id');



		$google_token = $this->request->session()->read("Tbl_faculty_members.google_id");

		$this->request->session()->destroy();

		if (!empty($google_token)) {

			$this->Session->delete('User.google_token');

			return $this->redirect(webURL . "google/logout.php");
			die;

		}

		return $this->redirect(webURL . "login");
		die;

		die;

	}



	public function accstdreffral($mobile = 0, $name = 0)
	{

		if (!empty($mobile)) {

			$numbers = array($mobile);

			/*Student account activation message*/

			$message = "Welcome to LifeSet. $name referred to you on LifeSet. Register your profile on https://lifeset.co.in/.Regards,Team LifeSet";



			$numbers = implode(',', $numbers);



			$res = $this->send_sms_template($numbers, $message);

			//	return 	$res;

		}

	}



	public function adminreferralList()
	{
		$this->loadModel('Tbl_student_referrals');
		$this->loadModel('Tbl_faculty_members');

		$pageid = isset($_GET['id']) ? $_GET['id'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';

		$Admincheckid = $this->request->session()->read("Admincheck.id");
		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute("
        SELECT 
            tsr.id,
            tsr.std_id,
            tsr.name,
            tsr.email,
            tsr.mobile,
            tsr.date,
            tfm.name AS std_name,
            ts.name AS college_name,
            CASE 
                -- Only webldate (valid) → register
                WHEN tfm_check.webldate IS NOT NULL 
                     AND tfm_check.webldate != '' 
                     AND tfm_check.webldate != '0000-00-00'
                     AND (tfm_check.mobldate IS NULL 
                          OR tfm_check.mobldate = '' 
                          OR tfm_check.mobldate = '0000-00-00')
                THEN 'Web Only'

                -- Both webldate & mobldate (valid) → install
                WHEN tfm_check.webldate IS NOT NULL 
                     AND tfm_check.webldate != '' 
                     AND tfm_check.webldate != '0000-00-00'
                     AND tfm_check.mobldate IS NOT NULL 
                     AND tfm_check.mobldate != '' 
                     AND tfm_check.mobldate != '0000-00-00'
                THEN 'Web & Mobile'

                -- Only mobldate (valid) → yes
                WHEN (tfm_check.webldate IS NULL 
                      OR tfm_check.webldate = '' 
                      OR tfm_check.webldate = '0000-00-00')
                     AND tfm_check.mobldate IS NOT NULL 
                     AND tfm_check.mobldate != '' 
                     AND tfm_check.mobldate != '0000-00-00'
                THEN 'Mobile Only'

                -- Nothing → no
                ELSE 'Pending'
            END AS downloaded
        FROM tbl_student_referrals tsr
        LEFT JOIN tbl_faculty_members tfm 
            ON tsr.std_id = tfm.id
        LEFT JOIN tbl_schools ts 
            ON ts.id = tfm.college_id
        LEFT JOIN tbl_faculty_members tfm_check
            ON tfm_check.mobile = tsr.mobile
        ORDER BY tsr.id DESC
    ");

		$cmspage1 = $stmt->fetchAll('assoc');

		$this->set('viewData', $cmspage1);

		if ($type == "delete") {
			$contentmem = $this->Tbl_student_referrals->get($pageid);
			if ($this->Tbl_student_referrals->delete($contentmem)) {
				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));
				return $this->redirect(webURL . 'admin-referral-list');
			}
		}
	}



	public function referralListExportData()
	{
		$conn = ConnectionManager::get('default');

		// Query with updated downloaded column logic (webldate + mobldate check)
		$stmt = $conn->execute("
          SELECT 
            tsr.id,
            tsr.std_id,
            tsr.name,
            tsr.email,
            tsr.mobile,
            tsr.date,
            tfm.name AS std_name,
            ts.name AS college_name,
            CASE 
                -- Only webldate (valid) → register
                WHEN tfm_check.webldate IS NOT NULL 
                     AND tfm_check.webldate != '' 
                     AND tfm_check.webldate != '0000-00-00'
                     AND (tfm_check.mobldate IS NULL 
                          OR tfm_check.mobldate = '' 
                          OR tfm_check.mobldate = '0000-00-00')
                THEN 'Web Only'

                -- Both webldate & mobldate (valid) → install
                WHEN tfm_check.webldate IS NOT NULL 
                     AND tfm_check.webldate != '' 
                     AND tfm_check.webldate != '0000-00-00'
                     AND tfm_check.mobldate IS NOT NULL 
                     AND tfm_check.mobldate != '' 
                     AND tfm_check.mobldate != '0000-00-00'
                THEN 'Web & Mobile'

                -- Only mobldate (valid) → yes
                WHEN (tfm_check.webldate IS NULL 
                      OR tfm_check.webldate = '' 
                      OR tfm_check.webldate = '0000-00-00')
                     AND tfm_check.mobldate IS NOT NULL 
                     AND tfm_check.mobldate != '' 
                     AND tfm_check.mobldate != '0000-00-00'
                THEN 'Mobile Only'

                -- Nothing → no
                ELSE 'Pending'
            END AS downloaded
        FROM tbl_student_referrals tsr
        LEFT JOIN tbl_faculty_members tfm 
            ON tsr.std_id = tfm.id
        LEFT JOIN tbl_schools ts 
            ON ts.id = tfm.college_id
        LEFT JOIN tbl_faculty_members tfm_check
            ON tfm_check.mobile = tsr.mobile
        ORDER BY tsr.id ASC
    ");

		$data = $stmt->fetchAll('assoc');

		// CSV file name
		$filename = "referral_list_" . date('Y-m-d_H-i-s') . ".csv";

		// Set headers for CSV download
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		// Open output stream
		$output = fopen('php://output', 'w');

		// Add CSV header row
		fputcsv($output, array('ID', 'Referral', 'Name', 'Email', 'Mobile', 'Sent On', 'User Type'));

		// Add CSV data rows
		foreach ($data as $row) {
			// Format date to YYYY-MM-DD
			$formattedDate = '';
			if (!empty($row['date'])) {
				$formattedDate = date('Y-m-d', strtotime($row['date']));
			}

			fputcsv($output, array(
				$row['id'],
				$row['std_name'],
				$row['name'],
				$row['email'],
				$row['mobile'],
				$formattedDate,
				$row['downloaded']
			));
		}

		fclose($output);
		exit;
	}




	public function studentreferralList()
	{

		$this->loadModel('Tbl_student_referrals');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_student_offers');



		$choffer = array('status' => 1);

		$choffer = $this->Tbl_student_offers->find("all", array("conditions" => $checnd, 'fields' => array('banner', 'terms')));

		$choffer->hydrate(false);

		$offerdata = $choffer->first();

		$this->set('offerdata', $offerdata);



		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($membid)) {

			// $respage1 =$this->Tbl_student_referrals->find("all",array('conditions'=>array('std_id'=>$membid)),array('order'=>array('id' =>'DESC')));

			$conn = ConnectionManager::get('default');

			$stmt = $conn->execute('select tbl_student_referrals.std_id,tbl_student_referrals.name,tbl_student_referrals.email,tbl_student_referrals.mobile,tbl_student_referrals.date,tbl_student_referrals.id,tbl_faculty_members.name as std_name,tbl_schools.name as college_name from tbl_student_referrals LEFT JOIN  tbl_faculty_members ON tbl_student_referrals.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_student_referrals.std_id =' . $membid . ' order by tbl_student_referrals.id desc ');

			$cmspage1 = $stmt->fetchAll('assoc');



		} else {

			// $respage1 =$this->Tbl_student_referrals->find("all",array('order'=>array('id' =>'DESC')));	

			$conn = ConnectionManager::get('default');

			$stmt = $conn->execute('select tbl_student_referrals.std_id,tbl_student_referrals.name,tbl_student_referrals.email,tbl_student_referrals.mobile,tbl_student_referrals.date,tbl_student_referrals.id,tbl_faculty_members.name as std_name,tbl_schools.name as college_name from tbl_student_referrals LEFT JOIN  tbl_faculty_members ON tbl_student_referrals.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id order by tbl_student_referrals.id desc ');

			$cmspage1 = $stmt->fetchAll('assoc');

		}

		/*	$respage1->hydrate(false);

														 $cmspage1 =  $respage1->toArray();	*/

		$this->set('viewData', $cmspage1);



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$respage = $this->Tbl_student_referrals->find("all", array('conditions' => array('id' => $pageid)));

		$respage->hydrate(false);

		$cmspage = $respage->first();

		$this->set('editviewData', $cmspage);



		if ($this->request->is('post')) {

			if (!empty($this->request->data['email'])) {

				if (!empty($this->request->data['id'])) {

					$checkdatamail = array('email' => $this->request->data['email'], 'id !=' => $this->request->data['id']);

				} else {
					$checkdatamail = array('email' => $this->request->data['email']);
				}

				$corschkmail = $this->Tbl_student_referrals->find("all", array("conditions" => $checkdatamail, 'fields' => array('id')));

				$corschkmail->hydrate(false);

				$teamcheckmail = $corschkmail->first();

				if (!empty($teamcheckmail)) {

					$this->Flash->success('This email id already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-referral-list');
					die;

				}

			}

			if (!empty($this->request->data['mobile'])) {

				if (!empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id']);

				} else {
					$checkdata = array('mobile' => $this->request->data['mobile']);
				}

				$corschk = $this->Tbl_student_referrals->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();



				if (!empty($teamcheck)) {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect(webURL . 'student-referral-list');
					die;

				}

			}

			$membdetail = array();

			if (empty($this->request->data['id'])) {

				$this->request->data['std_id'] = $membid;

			}



			$this->request->data['date'] = date('Y-m-d');

			if (empty($this->request->data['id'])) {



				$checnd = array('id' => $membid);

				$chec = $this->Tbl_faculty_members->find("all", array("conditions" => $checnd, 'fields' => array('name', 'email')));

				$chec->hydrate(false);

				$stddata = $chec->first();





				//--------------------student reffral connection mail 

				$this->accstdreffral($this->request->data['mobile'], $stddata['name']);



				$email = new EmailsController;

				$toEmails[] = $this->request->data['email'];

				//$toEmails[]='iktacdevelopers@gmail.com';

				$resultdata = $this->request->data;

				$resultdata['sender_name'] = $stddata['name'];



				$email->student_reffral_connection_mail($toEmails, $resultdata);



			}



			$dataToSave = $this->Tbl_student_referrals->newEntity($this->request->data);

			if ($this->Tbl_student_referrals->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-referral-list');
				die;



			}

		}

		if ($type == "delete") {

			$contentmem = $this->Tbl_student_referrals->get($pageid);

			if ($this->Tbl_student_referrals->delete($contentmem)) {



				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-referral-list');
				die;

			}

		}

	}

	public function studentvoiceRecord()
	{

		$this->loadModel('Tbl_student_audios');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_audio_banners');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_audios.id,tbl_student_audios.std_id,tbl_student_audios.video_name,tbl_student_audios.date,tbl_student_audios.status,tbl_faculty_members.name as std_name,tbl_schools.name as college_name,tbl_audio_banners.banner from tbl_student_audios LEFT JOIN  tbl_faculty_members ON tbl_student_audios.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id LEFT JOIN  tbl_audio_banners ON tbl_audio_banners.id = tbl_student_audios.banner_id where tbl_student_audios.std_id =' . $membid . ' order by tbl_student_audios.id desc ');

		$cmspage1 = $stmt->fetchAll('assoc');

		$this->set('viewData', $cmspage1);

		$stmtcheck = $conn->execute('select tbl_student_audios.id from tbl_student_audios where tbl_student_audios.std_id =' . $membid . ' AND tbl_student_audios.date =' . date('Y-m-d') . '');

		$cmscheck = $stmtcheck->fetch('assoc');

		$this->set('voiceDay', $cmscheck);



		if (isset($_GET['id'])) {
			$pageid = $_GET['id'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		//$membid=$this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");



		if ($type == "delete") {



			$checndaud = array('id' => $pageid);

			$chofferaud = $this->Tbl_student_audios->find("all", array("conditions" => $checndaud, 'fields' => array('id', 'video_name')));

			$chofferaud->hydrate(false);

			$resdata = $chofferaud->first();

			$contentmem = $this->Tbl_student_audios->get($pageid);

			if ($this->Tbl_student_audios->delete($contentmem)) {

				if (file_exists('voice_record/file/' . $resdata['video_name']) && !empty($resdata['video_name'])) {

					unlink('voice_record/file/' . $resdata['video_name']);

				}

				$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-voice-record');
				die;

			}

		}

	}

	public function studentvoiceScreen()
	{

		$this->loadModel('Tbl_student_audios');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_audio_banners');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_audios.id from tbl_student_audios LEFT JOIN  tbl_faculty_members ON tbl_student_audios.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_student_audios.std_id =' . $membid . '');

		$cmspage1 = $stmt->fetchAll('assoc');

		$date = date('Y-m-d');

		//$stmtcheck = $conn->execute("select tbl_student_audios.id from tbl_student_audios where tbl_student_audios.std_id =$membid AND tbl_student_audios.date =$date");

		//$cmscheck = $stmtcheck ->fetch('assoc');



		$cmscheckcnd = array('std_id' => $membid, 'date' => $date);

		$stmtcheck = $this->Tbl_student_audios->find("all", array("conditions" => $cmscheckcnd, 'fields' => array('id')));

		$stmtcheck->hydrate(false);

		$cmscheck = $stmtcheck->first();

		//print_r($cmspage1);die;

		$audio_banner_id = $this->request->session()->read("audio_banner_id");

		if (!empty($cmscheck)) {

			$this->Flash->success('You can upload only one voice sample in a day!', array('key' => 'acc_alert'));

			//return $this->redirect(webURL.'student-voice-record');die;

		} else if (count($cmspage1) >= 3) {

			$this->Flash->success('You can upload maximum 3 voice sample!', array('key' => 'acc_alert'));

			//return $this->redirect(webURL.'student-voice-record');die;

		}

		if (!empty($audio_banner_id)) {

			$checnd = array('status' => 1, 'id !=' => $audio_banner_id);

		} else {

			$checnd = array('status' => 1);

		}

		//$choffer =$this->Tbl_audio_banners->find("all",array("conditions"=>$checnd,'order' => 'rand()','fields'=>array('banner')));	

		$choffer = $this->Tbl_audio_banners->find('all')->where($checnd)->order('rand()')->limit(1);

		$choffer->hydrate(false);

		$offerdata = $choffer->first();

		$this->set('offerdata', $offerdata);



		$this->request->session()->write("audio_banner_id", $offerdata['id']);



		//print_r($offerdata);die;	  

	}

	public function studentvoicerecordScreen()
	{

		$this->loadModel('Tbl_student_audios');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_audio_banners');



		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_student_audios.id from tbl_student_audios LEFT JOIN  tbl_faculty_members ON tbl_student_audios.std_id = tbl_faculty_members.id  LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_student_audios.std_id =' . $membid . '');

		$cmspage1 = $stmt->fetchAll('assoc');

		$date = date('Y-m-d');

		//$stmtcheck = $conn->execute("select tbl_student_audios.id from tbl_student_audios where tbl_student_audios.std_id =$membid AND tbl_student_audios.date =$date");

		//$cmscheck = $stmtcheck ->fetch('assoc');



		$cmscheckcnd = array('std_id' => $membid, 'date' => $date);

		$stmtcheck = $this->Tbl_student_audios->find("all", array("conditions" => $cmscheckcnd, 'fields' => array('id')));

		$stmtcheck->hydrate(false);

		$cmscheck = $stmtcheck->first();

		//print_r($cmspage1);die;

		$audio_banner_id = $this->request->session()->read("audio_banner_id");

		if (!empty($cmscheck)) {

			$this->Flash->success('You can upload only one voice sample in a day!', array('key' => 'acc_alert'));

			//return $this->redirect(webURL.'student-voice-record');die;

		} else if (count($cmspage1) >= 3) {

			$this->Flash->success('You can upload maximum 3 voice sample!', array('key' => 'acc_alert'));

			//return $this->redirect(webURL.'student-voice-record');die;

		}

		if (!empty($audio_banner_id)) {

			$checnd = array('status' => 1, 'id !=' => $audio_banner_id);

		} else {

			$checnd = array('status' => 1);

		}

		//$choffer =$this->Tbl_audio_banners->find("all",array("conditions"=>$checnd,'order' => 'rand()','fields'=>array('banner')));	

		$choffer = $this->Tbl_audio_banners->find('all')->where($checnd)->order('rand()')->limit(1);

		$choffer->hydrate(false);

		$offerdata = $choffer->first();

		$this->set('offerdata', $offerdata);



		$this->request->session()->write("audio_banner_id", $offerdata['id']);



		//print_r($offerdata);die;	  

	}

	public function voicerecordUpload()
	{

		$this->loadModel('Tbl_student_audios');

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeId = $this->request->session()->read("Tbl_faculty_members.collegeid");





		$audio_banner_id = $this->request->session()->read("audio_banner_id");

		if (!empty($this->request->data['audio_data']['name'])) {





			$clg_audio_data = $file = $this->request->data['audio_data'];

			$clg_audio_data_name = $file = $this->request->data['audio_data']['name'];

			$clg_audio_data_path = $file = $this->request->data['audio_data']['tmp_name'];

			$save_clg_audio = time() . ".wav";





			$membdetail = array(

				'std_id' => $membid,

				'banner_id' => $audio_banner_id,

				'video_name' => $save_clg_audio,

				'status' => 1,

				'date' => date('Y-m-d'),

			);





			//  @move_uploaded_file($file,"voice_record/file/".$save_clg_audio);



			//	print_r($save_clg_audio);die;



			if (move_uploaded_file($file, WWW_ROOT . 'voice_record/file/' . $save_clg_audio)) {

				echo 'success';

			} else {
				echo 'error';
			}



			$dataToSave = $this->Tbl_student_audios->newEntity($membdetail);

			if ($this->Tbl_student_audios->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'student-voice-record');
				die;



			}

		}

		print_r($audio_banner_id);
		die;

	}



	/*

						  public function sendapplyedjobmailNew()

							{  		



							   $this->loadModel('Tbl_post_applieds');

								$conn = ConnectionManager::get('default'); 

								$list=array();

								//$member_array=array();

								$stmt = $conn->execute('select tbl_faculty_members.id,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_faculty_members.acc_password,tbl_posts.id as post_id,tbl_posts.title,tbl_post_applieds.date  from tbl_faculty_members 

								INNER JOIN  tbl_posts ON tbl_posts.post_by = tbl_faculty_members.id 

								INNER JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

								where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" group by tbl_faculty_members.id order by tbl_post_applieds.id desc');

								$datast = $stmt ->fetchAll('assoc');

								if(!empty($datast)){

									 foreach($datast as $datasts){



										$acc_password=base64_decode($datasts['acc_password']);

										$memid=$datasts['id'];

										$stmt3 = $conn->execute('select tbl_posts.title,tbl_post_applieds.post_id  from tbl_faculty_members 

										INNER JOIN  tbl_posts ON tbl_posts.post_by = tbl_faculty_members.id 

										INNER JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

										where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_faculty_members.id ='.$memid.'  group by tbl_post_applieds.post_id ');

										$datast3 = $stmt3 ->fetchAll('assoc');

										 $csthtml='';

										 $di=1;

								if(!empty($datast3)){

									 foreach($datast3 as $datast3s){



										$member_array=array();

										$stmt2 = $conn->execute('select tbl_faculty_members.id,tbl_posts.id as post_id,tbl_posts.title,tbl_post_applieds.date from tbl_faculty_members 

										INNER JOIN  tbl_posts ON tbl_posts.post_by = tbl_faculty_members.id 

										INNER JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

										INNER JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

										where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_faculty_members.id ='.$memid.' AND tbl_post_applieds.post_id ='.$datast3s['post_id'].'');

										$datast2 = $stmt2 ->fetchAll('assoc');

										$today=array(); $old=array();



										foreach($datast2 as $datast2s){

										   if(date('d-m-Y', strtotime($datast2s['date']))==date('d-m-Y')){

											 $today[]=$datast2s['post_id'];

										   }else{

											 $old[]=$datast2s['post_id'];

										   }

										}

									   $csthtml .="<p> ".$di.". ".$datast3s['title']." - ".count($today)." New and ".count($old)." Old Profiles</p>";

									   $di++; }

									 }

										 //$to ='rahuladhikari8477@gmail.com'; 

										$to =$datasts['email']; 

										$subject ="Job/Internships requirement On LifeSet";

										$headers= "MIME-Version: 1.0\r\n";

										$headers .= 'From: info@lifeset.co.in' . "\r\n";

										$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

										$body="<html>

											<head>

											<title>Job/Internships requirement On LifeSet</title>

											</head>

											<body>

											<table cellspacing='0px' cellpadding='2px' style='width:90%;margin:5px auto;font-family:Tahoma;border:0px solid #ccc;'>



											<tr>

												<td ><h3 style='font-size:16px; color:#9f57c7;font-weight:normal;' >Good Morning ".$datasts['name'].", </h3></td>

											</tr>

											<tr>

												<td ><p style='font-size:16px; color:#9f57c7;font-weight:normal;' >Please find the profiles against your active Job/Internships requirement published in LifeSet by you or on your behalf.  </p></td>

											</tr>

											<tr>

											<td ><p style='font-size:18px; color:#333;font-weight:normal;' >Response Details - </p></td>

											</tr>

											<tr>

											<td >

											  ".$csthtml."

											</td>

											</tr>

											<tr>

											<td>

											  <p> Below mentioned are your credentials to access the above-mentioned profiles.</p>

											  <p> URL - www.Lifeset.co.in</p>

											  <p> Your ID - ".$datasts['mobile']." - ".$acc_password."</p>

											</td>

											</tr>

											<tr>

											<td ><p style='font-size:16px; color:#333;font-weight:normal;' valign='left'>Regards,<br>

											Team LifeSet

											 </p></td>

											</tr>

											</table>

											</body>

											</html>";

									 if(mail($to,$subject,$body,$headers)){

									   echo 'mail send';  

									 }



								  }

								}



								die; 



							} */





	public function sendapplyedjobmail()
	{



		$this->loadModel('Tbl_post_applieds');

		$conn = ConnectionManager::get('default');

		$list = array();

		//$member_array=array();

		$stmt = $conn->execute('select tbl_company_accs.id,tbl_company_accs.name,tbl_company_accs.mobile,tbl_company_accs.email,tbl_company_accs.acc_password,tbl_posts.id as post_id,tbl_posts.title,tbl_post_applieds.date  from tbl_company_accs INNER JOIN  tbl_posts ON tbl_posts.post_by = tbl_company_accs.id LEFT JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_company_accs.mail_status =0 AND tbl_post_applieds.post_status =1 group by tbl_company_accs.id order by tbl_post_applieds.id desc');



		//$stmt = $conn->execute('select tbl_company_accs.id,tbl_company_accs.name,tbl_company_accs.mobile,tbl_company_accs.email,tbl_company_accs.acc_password,tbl_posts.id as post_id,tbl_posts.title,tbl_post_applieds.date  from tbl_company_accs INNER JOIN  tbl_posts ON tbl_posts.post_by = tbl_company_accs.id LEFT JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" group by tbl_company_accs.id AND tbl_company_accs.mail_status =0 AND tbl_post_applieds.post_status =1 order by tbl_post_applieds.id desc');

		$datast = $stmt->fetchAll('assoc');



		if (!empty($datast)) {

			foreach ($datast as $datasts) {

				$csthtml = '';

				$acc_password = base64_decode($datasts['acc_password']);

				$memid = $datasts['id'];

				$stmt3 = $conn->execute('select tbl_posts.title,tbl_post_applieds.post_id  from tbl_company_accs 

        		LEFT JOIN  tbl_posts ON tbl_posts.post_by = tbl_company_accs.id 

        		LEFT JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

        		where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_company_accs.id =' . $memid . ' AND tbl_post_applieds.post_status =1  group by tbl_post_applieds.post_id ');

				$datast3 = $stmt3->fetchAll('assoc');

				$di = 1;



				if (!empty($datast3)) {

					foreach ($datast3 as $datast3s) {

						$is_record = '';

						$csthtml .= "<h5>" . $datast3s['title'] . "</h5>";



						$member_array = array();

						$stmt2 = $conn->execute('select tbl_company_accs.id,tbl_posts.id as post_id,tbl_post_applieds.date ,tbl_post_applieds.post_status,tbl_post_applieds.id as apply_id,tbl_schools.name as college_name,tbl_faculty_members.name as member_name,tbl_faculty_members.id as member_id  from tbl_company_accs  

        		LEFT JOIN  tbl_posts ON tbl_posts.post_by = tbl_company_accs.id 

        		LEFT JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

        		INNER JOIN  tbl_faculty_members ON tbl_faculty_members.id= tbl_post_applieds.std_id 

        		LEFT JOIN  tbl_schools ON tbl_schools.id= tbl_faculty_members.college_id 

        		where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_post_applieds.post_status =1 AND tbl_company_accs.id =' . $memid . ' AND tbl_post_applieds.post_id =' . $datast3s['post_id'] . '');

						$datast2 = $stmt2->fetchAll('assoc');



						/*	  $member_array=array();

																																								   $stmt2 = $conn->execute('select tbl_company_accs.id from tbl_company_accs  

																																								   LEFT JOIN  tbl_posts ON tbl_posts.post_by = tbl_company_accs.id 

																																								   LEFT JOIN  tbl_post_applieds ON tbl_post_applieds.post_id = tbl_posts.id 

																																								   LEFT JOIN  tbl_faculty_members ON tbl_faculty_members.id= tbl_post_applieds.std_id 

																																								  LEFT JOIN  tbl_schools ON tbl_schools.id= tbl_faculty_members.college_id 

																																								   where tbl_posts.post_type ="Job" AND tbl_posts.status ="1" AND tbl_post_applieds.post_status =1 AND tbl_company_accs.id ='.$memid.' AND tbl_post_applieds.post_id ='.$datast3s['post_id'].'');

																																								   $datast2 = $stmt2 ->fetchAll('assoc');*/



						$today = array();
						$old = array();



						//	print_r($datast2);die;			

						if (!empty($datast2)) {

							foreach ($datast2 as $datast2s) {

								$datast2s['tech_skills'] = '';



								$is_record = 1;

								// print_r($datast2s);

								//echo '<br>';

								if ($datast2s['post_status'] == 1) {



									$datasave = array();

									$datasave['id'] = $datast2s['apply_id'];

									$datasave['post_status'] = 0;



									$dataToSave = $this->Tbl_post_applieds->newEntity($datasave);

									$this->Tbl_post_applieds->save($dataToSave);



									$today[] = $datast2s['post_id'];



								} else {

									$old[] = $datast2s['post_id'];

								}

								$csthtml .= "<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>" . $di . "<a href='https://lifeset.co.in/student-profile-view?id=" . $datast2s['member_id'] . "'> " . $datast2s['member_name'] . ' - ' . $datast2s['college_name'] . ' - ' . $datast2s['tech_skills'] . '</a></p>';

							}

							// $csthtml .="<p> ".$di.". ".$datast3s['title']." - ".count($today)." New and ".count($old)." Old Profiles</p>";

							$di++;



							// $csthtml .='<br>';

						}

						//echo  $csthtml; 

					}



				}

				// echo $csthtml.' '.$datasts['email'].'<br>';



				if (!empty($is_record)) {

					if (!empty($datast3)) {



						// $this->daily_applied_job_alert($datasts);



						$to = $datasts['email'];  //'rahuladhikari8477@gmail.com'; 



						$subject = "Subject Line  - Response against your Post | LifeSet";

						$headers = "MIME-Version: 1.0\r\n";

						$headers .= 'From: info@lifeset.co.in' . "\r\n";

						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";





						$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                        <html xmlns='http://www.w3.org/1999/xhtml'>

                        <head>

                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                        <title>	A Student Networking Site from Bharat</title>

                        </head>

                        

                        <body>

                        <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                              <tr>

                                <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td>A Student Networking Site from Bharat</td>

                              </tr>

                            </table></td>

                          </tr>

                          <tr>

                            <td align='center' style='background:#ededed; color:#000;'><br />

                              <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                              

                              <!-- Headding Section start here ------------------- -->

                              <h2 style='font-size:46px; font-weight:normal;'>Hi " . $datasts['name'] . ",</h2>

                              <h2 style='font-size:30px; font-weight:normal;'>Below mentioned are the responses that we receive against your active jobs</h2>

                             

                              

                            " . $csthtml . "

                        					

                        	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in' />

                              

                              </td>

                          </tr>

                         

                          <tr style='background:#34265f; color:#fff; font-size:18px;'>

                            <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                              <tr>

                                <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                              </tr>

                            </table></td>

                          </tr>

                        </table>

                        </body>

                        </html>";

						//	print_r($body);die;

						if (mail($to, $subject, $body, $headers)) {

							echo 'mail send';

						} else {

							echo 'mail error';

						}

						;
						die;



					}

				}

			}

		} else {

			echo 'no record found';

		}

		die;



	}





	//.............................. Payment  student......................



	public function payNow()
	{

		$this->loadModel('Tbl_payment_requests');



	}

	public function payRequest()
	{

		$this->loadModel('Tbl_payment_requests');



		$request = file_get_contents("php://input"); // gets the raw data

		$requestdata = json_decode($request, true);





		if ($requestdata) {

			$this->request->session()->write("razorpay_order_id", $requestdata['order_id']);

			$user_id = $this->request->session()->read("Tbl_faculty_members.id");

			$savedata['user_id'] = $user_id;

			$savedata['order_id'] = $requestdata['order_id'];

			$savedata['service_name'] = $requestdata['service_name'];

			$savedata['service_description'] = $requestdata['description'] ?? '';

			$savedata['trans_id'] = $requestdata['notes']['merchant_order_id'];

			$savedata['amount'] = $requestdata['amount'] / 100;

			$savedata['req_date'] = Date('Y-m-d\TH:i', time());

			$savedata['credit'] = $requestdata['credit'] ?? '';

			//	print_r($savedata);die;

			$dataToSave = $this->Tbl_payment_requests->newEntity($savedata);

			$this->Tbl_payment_requests->save($dataToSave);



			return $result;

		}



	}

	public function payResponse()
	{



		include($_SERVER['DOCUMENT_ROOT'] . '/webroot/razorpay/config.php');



		$success = true;

		$this->loadModel('Tbl_payment_requests');

		$this->loadModel('Tbl_payment_responses');

		$this->loadModel('Member_credit_points');

		//$this->request->session()->read("razorpay_order_id"); 

		if (empty($this->request->session()->read("razorpay_order_id"))) {

			//return $this->redirect(webURL.'pay-now');die;

		}

		$error = "Payment Failed";



		if (empty($_POST['razorpay_payment_id']) === false) {



			$razorPayId = $_POST['razorpay_payment_id'];



			$ch = curl_init('https://api.razorpay.com/v1/payments/' . $razorPayId . '');

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

			curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret); // Input your Razorpay Key Id and Secret Id here

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = json_decode(curl_exec($ch));



			//$response->status; // authorized



			// check that payment is authorized by razorpay or not

			if ($response->status == 'captured' || $response->status == 'authorized') {

				$success === true;

			} else {

				$success === false;

			}

			$order_id = $response->order_id;

		}



		$restd = $this->Tbl_payment_requests->find("all", array('conditions' => array('order_id' => $this->request->session()->read('razorpay_order_id'))));

		$restd->hydrate(false);

		$datadtl = $restd->first();





		$user_id = $this->request->session()->read("Tbl_faculty_members.id");



		if ($datadtl) {

			if ($success === true) {

				// print_r($res);die;	

				$savedata['payment_status'] = 'Success';



				$corsc = $this->Member_credit_points->find("all", array("conditions" => array('id' => $user_id), 'fields' => array('id', 'total_point')));

				$corsc->hydrate(false);

				$teamncredit = $corsc->first();



				$savecreadit = array(

					"cmp_id" => $user_id,

					"total_point" => $datadtl['credit'],

					"added_date" => date('Y-m-d'),

					"per_std_point" => 50,

				);

				if (!empty($teamncredit)) {

					$savecreadit["total_point"] = $teamncredit['total_point'] + $datadtl['credit'];

					$savecreadit["id"] = $teamncredit['id'];

				}

				$dataTocredit = $this->Member_credit_points->newEntity($savecreadit);

				$this->Member_credit_points->save($dataTocredit);





			} else {

				$savedata['payment_status'] = 'Failed';

			}



			$payment_id = $_POST['razorpay_payment_id'];



			$savedata['razorpay_payment_id'] = $payment_id;

			$savedata['user_id'] = $user_id;

			$savedata['order_id'] = $order_id;

			$savedata['trans_id'] = $datadtl['trans_id'];

			$savedata['amount'] = $datadtl['amount'];

			$savedata['payment_date'] = Date('Y-m-d\TH:i', time());





			$dataToSave = $this->Tbl_payment_responses->newEntity($savedata);

			$this->Tbl_payment_responses->save($dataToSave);



			$savereqdata['id'] = $datadtl['id'];

			$savereqdata['status'] = 1;

			$datareqSave = $this->Tbl_payment_requests->newEntity($savereqdata);

			$this->Tbl_payment_requests->save($datareqSave);



		}

		//$razorpay_order_id=$this->request->session()->read('razorpay_order_id');

		$session = $this->request->session();

		$session->delete('razorpay_order_id');

		/*  

														//email	

													  $to = $detail->email;

													 $from = $detail->name;

													 $info = ['name'=>$from,'package_name'=>$detorder->title,'username'=>$detail->email,'order_id'=>$res->order_id,'status'=>$res->payment_status,'payment_date'=>$res->payment_date,'amount'=>$res->amount];

													 $mail = new Mailer;  

													 $sendMail =   $mail->payment_order($to,$from,$info);

													*/

		// $this->Flash->success('Data successfully saved',array('key'=>'acc_alert'));

		return $this->redirect(webURL . 'pay-thanks/' . $datadtl['trans_id']);
		die;

	}

	public function payThanks($id = "")
	{

		$this->loadModel('Tbl_payment_responses');

		$user_id = $this->request->session()->read("Tbl_faculty_members.id");



		$restd = $this->Tbl_payment_responses->find("all", array('conditions' => array('trans_id' => $id, 'user_id' => $user_id)));

		$restd->hydrate(false);

		$datadtl = $restd->first();

		//	print_r($datadtl);die;

		$this->set('paresult', $datadtl);

	}



	public function payHistory()
	{



		$conn = ConnectionManager::get('default');

		$user_id = $this->request->session()->read("Tbl_faculty_members.id");

		$list = $conn->execute('select tbl_payment_responses.*,tbl_payment_requests.service_name from tbl_payment_responses INNER JOIN  tbl_payment_requests ON tbl_payment_requests.order_id = tbl_payment_responses.order_id where tbl_payment_responses.user_id =' . $user_id . '  order by tbl_payment_responses.id asc');

		$result = $list->fetchAll('assoc');



		//print_r($result);die;

		$this->set('paresult', $result);

	}



	//.............................. company Recuiter  student......................



	public function recruiterpaynow()
	{

		$this->loadModel('Tbl_payment_requests');

		//echo 'ok';die;

	}

	public function recruiterpayrequest()
	{







		$options = json_decode($_GET['objEmp']);

		$data = json_decode($_GET['objMem']);

		// print_R($data);die;



		$this->loadModel('Tbl_payment_requests');







		if (!empty($data)) {







			$this->request->session()->write("razorpay_order_id", $options->order_id);

			$user_id = $this->request->session()->read("company_accs.id");

			$savedata['user_id'] = $user_id;

			$savedata['order_id'] = $options->order_id;

			$savedata['service_name'] = $data->service_name;

			$savedata['service_description'] = $data->description ?? '';

			$savedata['trans_id'] = $options->notes->merchant_order_id;

			$savedata['amount'] = $options->amount / 100;

			$savedata['req_date'] = Date('Y-m-d\TH:i', time());

			if (!empty($data->credit)) {

				$savedata['type'] = 'company';

				$savedata['credit'] = $data->credit ?? 0;

				$savedata['no_of_post'] = 0;

			} else {

				$savedata['type'] = 'company-post';

				$savedata['credit'] = 0;

				$savedata['no_of_post'] = $data->no_of_post ?? 0;

			}

			//print_r($savedata);die;

			$dataToSave = $this->Tbl_payment_requests->newEntity($savedata);

			$this->Tbl_payment_requests->save($dataToSave);



			return $result;

		}



	}

	public function recruiterpayresponse()
	{



		// print_r($_POST);die;



		include($_SERVER['DOCUMENT_ROOT'] . '/webroot/razorpay/config.php');



		$success = true;

		$this->loadModel('Tbl_payment_requests');

		$this->loadModel('Tbl_payment_responses');

		$this->loadModel('Member_credit_points');

		$this->loadModel('Recruiter_credit_posts');



		//$this->request->session()->read("razorpay_order_id"); 

		if (empty($this->request->session()->read("razorpay_order_id"))) {

			//return $this->redirect(webURL.'pay-now');die;

		}

		$error = "Payment Failed";



		if (empty($_POST['razorpay_payment_id']) === false) {



			$razorPayId = $_POST['razorpay_payment_id'];



			$ch = curl_init('https://api.razorpay.com/v1/payments/' . $razorPayId . '');

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

			curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret); // Input your Razorpay Key Id and Secret Id here

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = json_decode(curl_exec($ch));







			// check that payment is authorized by razorpay or not

			if ($response->status == 'captured' || $response->status == 'authorized') {

				$success === true;

			} else {

				$success === false;

			}

			$order_id = $response->order_id;

		}



		$restd = $this->Tbl_payment_requests->find("all", array('conditions' => array('order_id' => $this->request->session()->read('razorpay_order_id'))));

		$restd->hydrate(false);

		$datadtl = $restd->first();

		//print_R($datadtl);die;

		$user_id = $this->request->session()->read("company_accs.id");



		if ($datadtl) {

			if ($success === true) {

				// print_r($res);die;	

				$savedata['payment_status'] = 'Success';



				if ($datadtl['type'] == 'company-post') {



					// adding post credits......................



					$corsc = $this->Recruiter_credit_posts->find("all", array("conditions" => array('cmp_id' => $user_id), 'fields' => array('id', 'total_posts')));

					$corsc->hydrate(false);

					$teamncredit = $corsc->first();



					if (!empty($teamncredit)) {



						$savecreadit = array(

							"cmp_id" => $user_id,

							"total_posts" => $teamncredit['total_posts'] + $datadtl['no_of_post'],

							"updated" => date('Y-m-d h:i:s a')

						);

						$savecreadit["id"] = $teamncredit['id'];

					} else {



						$savecreadit = array(

							"cmp_id" => $user_id,

							"total_posts" => $datadtl['no_of_post'],

							"updated" => date('Y-m-d h:i:s a')

						);

					}

					$dataTocredit = $this->Recruiter_credit_posts->newEntity($savecreadit);

					$this->Recruiter_credit_posts->save($dataTocredit);



				} else {

					// adding member credits......................

					$corsc = $this->Member_credit_points->find("all", array("conditions" => array('cmp_id' => $user_id), 'fields' => array('id', 'total_point')));

					$corsc->hydrate(false);

					$teamncredit = $corsc->first();



					$savecreadit = array(

						"cmp_id" => $user_id,

						"total_point" => $datadtl['credit'],

						"added_date" => date('Y-m-d'),

						"per_std_point" => 50,

					);

					if (!empty($teamncredit)) {

						$savecreadit["total_point"] = $teamncredit['total_point'] + $datadtl['credit'];

						$savecreadit["id"] = $teamncredit['id'];

					}

					$dataTocredit = $this->Member_credit_points->newEntity($savecreadit);

					$this->Member_credit_points->save($dataTocredit);

				}



			} else {

				$savedata['payment_status'] = 'Failed';

			}







			$payment_id = $_POST['razorpay_payment_id'];



			$savedata['razorpay_payment_id'] = $payment_id;

			$savedata['user_id'] = $user_id;

			$savedata['order_id'] = $order_id;

			$savedata['trans_id'] = $datadtl['trans_id'];

			$savedata['amount'] = $datadtl['amount'];

			$savedata['payment_date'] = Date('Y-m-d\TH:i', time());





			$dataToSave = $this->Tbl_payment_responses->newEntity($savedata);

			$this->Tbl_payment_responses->save($dataToSave);





			$savereqdata['id'] = $datadtl['id'];

			$savereqdata['status'] = 1;

			$datareqSave = $this->Tbl_payment_requests->newEntity($savereqdata);

			$this->Tbl_payment_requests->save($datareqSave);



		}



		//$razorpay_order_id=$this->request->session()->read('razorpay_order_id');

		$session = $this->request->session();

		$session->delete('razorpay_order_id');



		/*  

														//email	

													  $to = $detail->email;

													 $from = $detail->name;

													 $info = ['name'=>$from,'package_name'=>$detorder->title,'username'=>$detail->email,'order_id'=>$res->order_id,'status'=>$res->payment_status,'payment_date'=>$res->payment_date,'amount'=>$res->amount];

													 $mail = new Mailer;  

													 $sendMail =   $mail->payment_order($to,$from,$info);

													*/

		// $this->Flash->success('Data successfully saved',array('key'=>'acc_alert'));

		return $this->redirect(webURL . 'recruiter/pay-thanks/' . $datadtl['trans_id']);
		die;

	}

	public function recruiterpaythanks($id = "")
	{



		//echo $id;die;

		$this->loadModel('Tbl_payment_responses');

		$user_id = $this->request->session()->read("company_accs.id");



		$restd = $this->Tbl_payment_responses->find("all", array('conditions' => array('trans_id' => $id, 'user_id' => $user_id)));

		$restd->hydrate(false);

		$datadtl = $restd->first();

		//	print_r($datadtl);die;

		$this->set('paresult', $datadtl);

	}



	public function recruiterpayhistory()
	{



		$conn = ConnectionManager::get('default');

		$user_id = $this->request->session()->read("company_accs.id");

		$list = $conn->execute('select tbl_payment_responses.*,tbl_payment_requests.service_name,tbl_payment_requests.type from tbl_payment_responses INNER JOIN  tbl_payment_requests ON tbl_payment_requests.order_id = tbl_payment_responses.order_id where tbl_payment_responses.user_id =' . $user_id . '  order by tbl_payment_responses.id asc');

		$result = $list->fetchAll('assoc');



		//print_r($result);die;

		$this->set('paresult', $result);

	}



	/*------------------ job performance start -----------------*/



	public function jobperformanceList()
	{

		$conn = ConnectionManager::get('default');

		$this->loadModel('Tbl_schools');



		$cmpid = $this->request->session()->read("company_accs.id");

		$coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");



		if (!empty($cmpid)) {



			if (isset($_GET['job']) && $_GET['job'] > 0) {

				$jobId = $_GET['job'];

				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id   INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_post_applieds.post_id = ' . $jobId . ' AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.company_id=' . $cmpid . ' AND tbl_posts.post_type IN ("Job","Internship") GROUP BY tbl_schools.id');

				$cmspage = $respage->fetchAll('assoc');



			} else {



				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id  INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.company_id=' . $cmpid . ' AND tbl_posts.post_type IN ("Job","Internship") GROUP BY tbl_schools.id');

				$cmspage = $respage->fetchAll('assoc');



			}

		} else if (!empty($coll_id)) {

			if (isset($_GET['job']) && $_GET['job'] > 0) {

				$jobId = $_GET['job'];

				// $respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id   INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_post_applieds.post_id = '.$jobId.' AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.college_id = '.$coll_id.' AND (tbl_posts.company_id="0" OR tbl_posts.company_id="") AND tbl_posts.post_type IN ("Job","Internship") GROUP BY tbl_schools.id');

				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id  INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.post_type IN ("Job","Internship")  AND tbl_post_applieds.post_id = ' . $jobId . '  AND (tbl_posts.company_id = "0" OR tbl_posts.company_id = "")  AND tbl_schools.id = ' . $coll_id . ' GROUP BY tbl_schools.id');



				$cmspage = $respage->fetchAll('assoc');



			} else {



				// $respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id  INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.post_type IN ("Job","Internship") AND tbl_posts.college_id = '.$coll_id.'  AND (tbl_posts.company_id = "0" OR tbl_posts.company_id = "")  GROUP BY tbl_schools.id');

				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id  INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.post_type IN ("Job","Internship")   AND (tbl_posts.company_id = "0" OR tbl_posts.company_id = "")  AND tbl_schools.id = ' . $coll_id . ' GROUP BY tbl_schools.id');



				$cmspage = $respage->fetchAll('assoc');



				// print_r($cmspage);die;

			}

		} else {



			if (isset($_GET['job']) && $_GET['job'] > 0) {

				$jobId = $_GET['job'];

				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id   INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_post_applieds.post_id = ' . $jobId . ' AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.post_type IN ("Job","Internship") GROUP BY tbl_schools.id');

				$cmspage = $respage->fetchAll('assoc');



			} else {



				$respage = $conn->execute('select tbl_schools.* from tbl_schools  INNER JOIN  tbl_faculty_members ON tbl_faculty_members.college_id = tbl_schools.id INNER JOIN  tbl_post_applieds ON tbl_post_applieds.std_id = tbl_faculty_members.id  INNER JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_courses ON tbl_courses.id = tbl_member_details.course  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.status=1 AND (tbl_member_details.year =tbl_courses.duration OR tbl_member_details.is_alumini="Alumni") AND tbl_posts.title !="" AND tbl_posts.status=1 AND tbl_posts.post_type IN ("Job","Internship") GROUP BY tbl_schools.id');

				$cmspage = $respage->fetchAll('assoc');



			}

		}

		// $respage =$this->Tbl_schools->find("all",array('conditions'=>array('new_request'=>0)),array('order'=>array('id' =>'DESC')));	

		//$respage->hydrate(false);

		// $cmspage =  $respage->toArray();





		$this->set('schoolList', $cmspage);

		//college_id

		$this->loadModel('Tbl_posts');

		if (!empty($cmpid)) {

			$condition = array('title !=' => '', 'status' => 1, 'company_id' => $cmpid, 'post_type IN' => array('Job', 'Internship'));

		} else if (!empty($coll_id)) {

			$condition = array('title !=' => '', 'status' => 1, 'college_id' => $coll_id, array('OR' => array('company_id' => 0, 'company_id' => '')), 'post_type IN' => array('Job', 'Internship'));

		} else {

			$condition = array('title !=' => '', 'status' => 1, 'post_type IN' => array('Job', 'Internship'));

		}

		$resjob = $this->Tbl_posts->find("all", array('conditions' => $condition, 'order' => array('title' => 'asc')));

		$resjob->hydrate(false);

		$postresult = $resjob->toArray();

		$this->set('postList', $postresult);



	}



	/*------------------ job performance  end-----------------*/



	public function storeschoolDetail()
	{

		$this->loadModel('Tbl_school_sections');

		if ($this->request->is('post')) {

			$savedata = array();



			if (!empty($this->request->data['sec_image']['name'])) {

				$sec_image = $file = $this->request->data['sec_image'];

				$clg_logo_name = $file = $this->request->data['sec_image']['name'];

				$clg_logo_path = $file = $this->request->data['sec_image']['tmp_name'];

				$save_clg_logo = time() . $clg_logo_name;



				@move_uploaded_file($file, "img/school/section/" . $save_clg_logo);

				$this->request->data['image'] = $save_clg_logo;

			}

			unset($this->request->data['sec_image']);

			$this->request->data['school_id'] = $this->request->session()->read("Tbl_faculty_members.collegeid");

			$dataToSave = $this->Tbl_school_sections->newEntity($this->request->data);

			if ($this->Tbl_school_sections->save($dataToSave)) {

				$this->Flash->success('Data successfully saved', array('key' => 'acc_alert'));

				return $this->redirect(webURL . 'member-college');
				die;

			}

		}



	}

	public function deleteschoolsection($id = 0)
	{

		$this->loadModel('Tbl_school_sections');



		$checndaud = array('id' => $id);

		$chofferaud = $this->Tbl_school_sections->find("all", array("conditions" => $checndaud));

		$chofferaud->hydrate(false);

		$resdata = $chofferaud->first();



		$contentmem = $this->Tbl_school_sections->get($id);

		if ($this->Tbl_school_sections->delete($contentmem)) {

			if (file_exists('img/school/section/' . $resdata['image']) && !empty($resdata['image'])) {

				unlink('img/school/section/' . $resdata['image']);

			}

			$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

			return $this->redirect(webURL . 'member-college');
			die;

		}

	}







	public function institutes()
	{

		$this->loadModel('Tbl_schools');

		if (!empty($_GET['key'])) {



			$key = $_GET['key'];

			$cat = array();

			$this->loadModel('Tbl_courses');

			if (strtolower($key) == 'ug' || strtolower($key) == 'pg') {

				$respage = $this->Tbl_courses->find("all", array('conditions' => array('status' => 1, 'level' => $key)));

			} else {

				$respage = $this->Tbl_courses->find("all", [

					'conditions' => [

						'status' => 1,

						'OR' => [

							'name LIKE' => '%' . $key . '%',

							'specialization LIKE' => '%' . $key . '%',

							'stream_title LIKE' => '%' . $key . '%',

							'degree_awarded LIKE' => '%' . $key . '%'

						]

					]

				]);

			}

			$respage->hydrate(false);

			$cmsp = $respage->toArray();

			if (!empty($cmsp)) {

				foreach ($cmsp as $cmspages) {
					$cat[] = $cmspages['college_id'];
				}

			}





			if (!empty($cat)) {

				$this->paginate = [

					'limit' => 12,

					'conditions' => [

						'new_request' => 0,

						'OR' => [

							'id IN' => $cat,

							'name LIKE' => '%' . $key . '%',

							'email LIKE' => '%' . $key . '%',

							'mobile LIKE' => '%' . $key . '%',

							'address LIKE' => '%' . $key . '%',

							'state LIKE' => '%' . $key . '%',

							'district LIKE' => '%' . $key . '%',

							'city LIKE' => '%' . $key . '%'

						]

					],

					'order' => ['updated' => 'desc']

				];



			} else {

				$this->paginate = [

					'limit' => 12,

					'conditions' => [

						'new_request' => 0,

						'OR' => [

							'name LIKE' => '%' . $key . '%',

							'email LIKE' => '%' . $key . '%',

							'mobile LIKE' => '%' . $key . '%',

							'address LIKE' => '%' . $key . '%',

							'state LIKE' => '%' . $key . '%',

							'district LIKE' => '%' . $key . '%',

							'city LIKE' => '%' . $key . '%'

						]

					],

					'order' => ['updated' => 'desc']

				];

			}

		} else {

			$this->paginate = (array('limit' => 12, "conditions" => array('new_request' => 0), 'order' => array('updated' => 'desc')));

		}

		$cmspage = $this->paginate('Tbl_schools');

		$this->set('viewData', $cmspage);

	}

	public function instituteDetail($ins = 0)
	{

		$this->loadModel('Tbl_schools');

		if (!empty($ins)) {



			$id = base64_decode($ins);

			$restd = $this->Tbl_schools->find("all", array('conditions' => array('id' => $id)));

			$restd->hydrate(false);

			$datast = $restd->first();

			$this->set('detail', $datast);

		} else {

			return $this->redirect(webURL . 'company-dashboard');
			die;

		}

	}





	public function studenttpoSummary()
	{

		$respage = '';

		$search_college_id = '';



		$id = $this->request->session()->read("Tbl_faculty_members.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($_GET['course'])) {



			$lavel = 3;

			$conn = ConnectionManager::get('default');

			$search_stream = ' AND tbl_courses.stream_title ="' . $_GET['cat'] . '"';

			$search_course = ' AND tbl_courses.name ="' . $_GET['course'] . '"';

			if (!empty($id)) {

				$search_college_id = ' AND tbl_courses.college_id ="' . $collegeid . '"';

			}

			$stmt = $conn->execute('select tbl_member_details.*,tbl_faculty_members.*  from tbl_faculty_members LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id where tbl_faculty_members.status=1 ' . $search_stream . ' ' . $search_course . ' ' . $search_college_id . ' ');

			$cmspage = $stmt->fetchAll('assoc');





		} else if (!empty($_GET['cat'])) {



			$lavel = 2;

			$this->loadModel('Tbl_courses');

			if (!empty($id)) {

				$respage = $this->Tbl_courses->find("all", array('conditions' => array('college_id' => $collegeid, 'stream_title' => $_GET['cat']), 'order' => array('id' => 'DESC')))->group('name');



			} else {

				$respage = $this->Tbl_courses->find("all", array('conditions' => array('stream_title' => $_GET['cat']), 'order' => array('id' => 'DESC')))->group('name');



			}

			$respage->hydrate(false);

			$cmspage = $respage->toArray();



		} else {

			$lavel = 1;

			$conn = ConnectionManager::get('default');

			if (!empty($id)) {

				$search_college_id = ' AND tbl_courses.college_id ="' . $collegeid . '"';

			}



			$stmt = $conn->execute('select tbl_course_categorys.*  from tbl_course_categorys LEFT JOIN  tbl_courses ON tbl_course_categorys.name = tbl_courses.stream_title where tbl_courses.status=1 ' . $search_college_id . '   GROUP BY  tbl_course_categorys.name');

			$cmspage = $stmt->fetchAll('assoc');



		}

		$this->set('categorylist', $cmspage);

		$this->set('lavel', $lavel);



	}

	public function studentjobperformanceSummary()
	{



		$cmpid = $this->request->session()->read("company_accs.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$search_jobid = '';

		$conn = ConnectionManager::get('default');



		$search_key = '';

		if (!empty($_GET['id']) && $_GET['job']) {

			$search_jobid = ' AND tbl_post_applieds.post_id ="' . $_GET['id'] . '"';

			if (!empty($_GET['key'])) {

				$search = $_GET['key'];

				$search_key = ' AND tbl_faculty_members.name LIKE "%' . $search . '%"';

			}

			$stmt_year = $conn->execute('select tbl_posts.id as postid,tbl_faculty_members.*,tbl_member_details.*,tbl_post_applieds.post_status from tbl_post_applieds LEFT JOIN  tbl_faculty_members ON tbl_post_applieds.std_id = tbl_faculty_members.id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id   where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.college_id = ' . $collegeid . '  ' . $search_jobid . ' ' . $search_key . '  AND tbl_faculty_members.status=1 GROUP BY tbl_faculty_members.id');

			$results = $stmt_year->fetchAll('assoc');



		} else {

			if (!empty($_GET['key'])) {

				$search = $_GET['key'];

				$search_key = ' AND tbl_posts.title LIKE "%' . $search . '%"';

			}

			$stmt_year = $conn->execute('select tbl_posts.* from tbl_post_applieds LEFT JOIN  tbl_faculty_members ON tbl_post_applieds.std_id = tbl_faculty_members.id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id   where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.college_id = ' . $collegeid . '  ' . $search_jobid . ' ' . $search_key . '   AND tbl_faculty_members.status=1 GROUP BY tbl_posts.id');

			$results = $stmt_year->fetchAll('assoc');

		}



		$this->set('postList', $results);

	}



	public function studentjobActivity()
	{



		$cmpid = $this->request->session()->read("company_accs.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");



		$search_jobid = '';

		$search_course = '';

		$search_cat = '';

		$search_year = '';

		$conn = ConnectionManager::get('default');

		if (!empty($_GET['sid'])) {



			$stmt = $conn->execute('select tbl_faculty_members.*,tbl_member_details.*,tbl_faculty_members.id as id from tbl_faculty_members  INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id  where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.college_id = ' . $collegeid . '  AND tbl_faculty_members.id=' . $_GET['sid'] . ' AND tbl_faculty_members.status=1');

			$resulsd = $stmt->fetch('assoc');

			$this->set('stdata', $resulsd);



			$search_jobid = ' AND tbl_post_applieds.std_id  ="' . $_GET['sid'] . '"';

			$stmt_year = $conn->execute('select tbl_posts.* from tbl_post_applieds LEFT JOIN  tbl_faculty_members ON tbl_post_applieds.std_id = tbl_faculty_members.id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id   where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.college_id = ' . $collegeid . '  ' . $search_jobid . '  AND tbl_faculty_members.status=1 GROUP BY tbl_posts.id');

			$results = $stmt_year->fetchAll('assoc');



		} else {



			//if(!empty($_GET['cat'])){ $search_course=' AND tbl_member_details.category  ="'.$_GET['cat'].'"'; }

			if (!empty($_GET['course'])) {
				$search_cat = ' AND tbl_member_details.course  ="' . $_GET['course'] . '"';
			}

			if (!empty($_GET['year'])) {
				$search_year = ' AND tbl_member_details.year  ="' . $_GET['year'] . '"';
			}



			$stmt_year = $conn->execute('select tbl_posts.id as postid,tbl_faculty_members.*,tbl_member_details.*,tbl_faculty_members.id as id,tbl_post_applieds.post_status from tbl_post_applieds LEFT JOIN  tbl_faculty_members ON tbl_post_applieds.std_id = tbl_faculty_members.id INNER JOIN  tbl_member_details ON tbl_faculty_members.id = tbl_member_details.acc_id LEFT JOIN  tbl_posts ON tbl_posts.id = tbl_post_applieds.post_id   where tbl_faculty_members.profileType in (3,4) AND tbl_faculty_members.college_id = ' . $collegeid . ' ' . $search_cat . ' ' . $search_course . ' ' . $search_year . '  AND tbl_faculty_members.status=1 GROUP BY tbl_faculty_members.id');

			$results = $stmt_year->fetchAll('assoc');

		}



		$this->set('postList', $results);

	}





	public function assignjobtocompanyList()
	{

		$this->loadModel('Tbl_posts');

		$this->loadModel('Tbl_company_accs');

		$this->loadModel('Member_credit_points');

		$respage = '';

		$type = 'Job';



		$total_jobs = 0;

		if (!empty($_GET['search']) && $_GET['search'] != 'all') {



			$respage = $this->Tbl_posts->find("all", array('conditions' => array('company_name LIKE' => '%' . $_GET['search'] . '%', 'post_type' => $type, 'company_id' => 0), 'order' => array('updated' => 'desc')));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

		} else {



			$respage = $this->Tbl_posts->find("all", array('conditions' => array('post_type' => $type, 'company_id' => 0), 'order' => array('updated' => 'desc')));

			$respage->hydrate(false);

			$cmspage = $respage->toArray();

		}

		$total_jobs = count($cmspage);



		$this->set('total_jobs', $total_jobs);

		$this->set('BlogPage', $cmspage);



		if (isset($_GET['pageid'])) {
			$pageid = $_GET['pageid'];
		} else {
			$pageid = '';
		}

		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = '';
		}



		// deleting post................

		if ($type == "delete") {

			if (!empty($cmspage)) {

				$content = $this->Tbl_posts->get($pageid);

				if ($this->Tbl_posts->delete($content)) {



					if (file_exists('img/Post/' . $cmspage['image']) && !empty($cmspage['image'])) {

						unlink('img/Post/' . $cmspage['image']);

					}

					$this->Flash->success('Data successfully deleted', array('key' => 'acc_alert'));

					return $this->redirect($_SERVER['HTTP_REFERER']);
					die;

				}

			}

		}

		// create company account and migrate jobs............

		if ($this->request->is('post')) {

			$postids = $this->request->data['postids'];

			if (!empty($postids)) {

				if (empty($this->request->data['id'])) {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'status' => 1);

					$cpassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

					$this->request->data['acc_password'] = base64_encode($cpassword);

				} else {

					$checkdata = array('mobile' => $this->request->data['mobile'], 'id !=' => $this->request->data['id'], 'status' => 1);

				}

				$corschk = $this->Tbl_company_accs->find("all", array("conditions" => $checkdata, 'fields' => array('id')));

				$corschk->hydrate(false);

				$teamcheck = $corschk->first();

				if (empty($teamcheck)) {

					$this->request->data['status'] = 1;



					$dataToSave = $this->Tbl_company_accs->newEntity($this->request->data);

					if ($this->Tbl_company_accs->save($dataToSave)) {

						if (isset($this->request->data['credit_points']) && !empty($this->request->data['credit_points'])) {

							$corsc = $this->Tbl_company_accs->find("all", array("conditions" => array('mobile' => $this->request->data['mobile']), 'fields' => array('id')));

							$corsc->hydrate(false);

							$teamnew = $corsc->first();



							$savecreadit = array(

								"cmp_id" => $teamnew['id'],

								"total_point" => $this->request->data['credit_points'],

								"added_date" => date('Y-m-d'),

								"per_std_point" => 50,

							);

							$dataTocredit = $this->Member_credit_points->newEntity($savecreadit);

							$this->Member_credit_points->save($dataTocredit);

						}



						if ($this->request->data['mail_status'] == 0) {





							$to = webEmail . ',' . $this->request->data['email'];

							$subject = "Company Account Registered Successfully On LifeSet";

							$headers = "MIME-Version: 1.0\r\n";

							$headers .= 'From: info@lifeset.co.in' . "\r\n";

							$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

							$body = "<html>

    					<head>

    					<title>Company Account Registered Successfully On LifeSet</title>

    					</head>

    					<body>

    					<table cellspacing='0px' cellpadding='2px' style='width:80%;margin:0px auto;font-family:Tahoma;border:3px solid #ccc;'>

    					<tr>

    					<td style='background:#fec303; padding-left: 35px; padding-right:35px;' valign='middle' ><h4 style='font-size:18px; margin:7px auto; color:#fff;font-weight:normal;'> Account Details  :</h4></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Pincode  : " . $this->request->data['pincode'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>State  : " . $this->request->data['state'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>District  : " . $this->request->data['district'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>City  : " . $this->request->data['city'] . "  </h3></td>

    					</tr>

    					<tr>

    					<td style='padding-left: 35px; padding-right:35px;'><h3 style='font-size:21px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Address  : " . $this->request->data['address'] . "  </h3></td>

    					</tr>

    					</table>

    					</body>

    					</html>";

							$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                            <html xmlns='http://www.w3.org/1999/xhtml'>

                            <head>

                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

                            <title>Company Account Registered Successfully On LifeSet</title>

                            </head>

                            

                            <body>

                            <table width='700' border='0' align='center' cellpadding='10' cellspacing='0' style='font-family:Arial, Tahoma, Geneva, sans-serif'>

                              <tr style='background:#34265f; color:#fff; font-size:18px;'>

                                <td ><table width='100%' border='0' cellspacing='0' cellpadding='0'>

                                  <tr>

                                    <td width='120'><a href='https://lifeset.co.in'><img src='https://lifeset.co.in/mailtemplates/logo.png' width='90' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                    <td>A Student Networking Site from Bharat</td>

                                  </tr>

                                </table></td>

                              </tr>

                              <tr>

                                <td align='center' style='background:#ededed; color:#000;'><br />

                                  <img src='https://lifeset.co.in/webroot/mailtemplates/email-icon.png' width='140' alt='' longdesc='https://lifeset.co.in' />

                                  

                                  <!-- Headding Section start here ------------------- -->

                                  <h2 style='font-size:46px; font-weight:normal;'>Hi " . $this->request->data['name'] . ",</h2>

                                  <h2 style='font-size:30px; font-weight:normal;'>Thank you for registering with LifeSet, the World’s 1st Students’ Networking Platform.</h2>

                                 

                                  

                                        <!-- Content Section start here ------------------- -->

                                  <p style='font-size:26px; font-weight:normal;'>Here you can access students' profiles and contact compatible candidates to meet your hiring needs.</p>

                                                              

                                  

                        		<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Name  : " . $this->request->data['name'] . " </p>

    							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Email  : " . $this->request->data['email'] . "  </p>

    							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Mobile Number  : " . $this->request->data['mobile'] . "</p>

      							<p style='font-size:16px; color:#333;font-weight:normal; margin:7px auto;' valign='middle'>Password  : " . $cpassword . "</p>

                            					

                            	  <img src='https://lifeset.co.in/webroot/mailtemplates/image.png' width='400' alt='' longdesc='https://lifeset.co.in/cmp-profile' />

                                  

                                  </td>

                              </tr>

                              <tr style='background:#ededed; color:#000;'>

                                <td align='center' style='padding:30px'>

                                

                                            <!-- Action Button Section start here ------------------- -->

                                <a href='https://lifeset.co.in/login' style='color:rgb(255,255,255);font-size:20px;border-radius:6px; padding:15px 30px; display:inline-block; background:#006;' rel='noreferrer'>Login Now!</a></td>

                              </tr>

                              <tr style='background:#34265f; color:#fff; font-size:18px;'>

                                <td><table border='0' align='center' cellpadding='10' cellspacing='0'>

                                  <tr>

                                    <td><a href='https://www.facebook.com/Lifeset-123147182410910/'><img src='https://lifeset.co.in/webroot/mailtemplates/facebook.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                    <td><a href='https://twitter.com/LifesetIndia'><img src='https://lifeset.co.in/webroot/mailtemplates/twitter.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                    <td><a href='https://play.google.com/store/apps/details?id=com.lifeset.team'><img src='https://lifeset.co.in/webroot/mailtemplates/lifeset.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                    <td><a href='https://www.linkedin.com/company/lifeset-a-students-community-app/'><img src='https://lifeset.co.in/webroot/mailtemplates/linkedin.png' width='40' alt='' longdesc='https://lifeset.co.in' /></a></td>

                                  </tr>

                                </table></td>

                              </tr>

                            </table>

                            </body>

                            </html>";

							mail($to, $subject, $body, $headers);



							$updtdt['id'] = $dataToSave['id'];

							$updtdt['mail_status'] = 1;

							$dataupdtToSave = $this->Tbl_company_accs->newEntity($updtdt);

							$this->Tbl_company_accs->save($dataupdtToSave);



						}



						$postids = explode(',', $postids);

						foreach ($postids as $postid) {

							$corsc = $this->Tbl_posts->find("all", array("conditions" => array('id' => $postid), 'fields' => array('id')));

							$corsc->hydrate(false);

							$tpost = $corsc->first();



							if (!empty($tpost)) {

								$savecpost = array(

									"id" => $tpost['id'],

									"company_id" => $teamnew['id'],

									"status" => 1,

									"company_name" => $this->request->data['name']

								);

								$datapost = $this->Tbl_posts->newEntity($savecpost);

								$this->Tbl_posts->save($datapost);

							}

						}



						$this->Flash->success('Account successfully created', array('key' => 'acc_alert'));

						return $this->redirect('job-migration-list');
						die;

					}

				} else {

					$this->Flash->success('This mobile number already exist.', array('key' => 'acc_alert'));

					return $this->redirect($_SERVER['HTTP_REFERER']);
					die;

				}

			} else {

				$this->Flash->success('Invalid request.', array('key' => 'acc_alert'));

				return $this->redirect($_SERVER['HTTP_REFERER']);
				die;

			}

		}



	}





	public function exportstudentList()
	{

		$this->loadModel('Tbl_faculty_members');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'college_id' => $collegeid, 'new_request' => 0)), array('order' => array('id' => 'DESC')));



		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'new_request' => 0)), array('order' => array('id' => 'DESC')));

		}

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}


	public function exportstudentList1()
	{
		$this->loadModel('Tbl_posts');

		$Admincheckid = $this->request->session()->read("Admincheck.id");
		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		if (!empty($collegeid)) {
			$respage = $this->Tbl_posts->find("all", [
				'conditions' => ['college_id' => $collegeid],
				'order' => ['id' => 'DESC']
			]);
		} else {
			$respage = $this->Tbl_posts->find("all", [
				'order' => ['id' => 'DESC']
			]);
		}

		$respage = $respage->hydrate(false);
		$cmspage = $respage->toArray();

		// CSV Export headers
		$filename = "post_list_" . date("Y-m-d_H-i-s") . ".csv";
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		$output = fopen("php://output", "w");

		// CSV Header Row
		fputcsv($output, ['ID', 'Post Type', 'Category', 'Title', 'Course', 'Created', 'Updated', 'Status']);

		// Loop and Write rows
		foreach ($cmspage as $row) {
			fputcsv($output, [
				$row['id'],
				$row['post_type'],
				$row['category'],
				$row['title'],
				$row['course'],
				date('Y-m-d H:i:s', strtotime($row['created'])),
				date('Y-m-d H:i:s', strtotime($row['updated'])),
				$row['status'] == 1 ? 'Published' : 'Unpublished',
			]);
		}

		fclose($output);
		exit;

	}


	public function exportstudentList2()
	{
		$this->loadModel('Tbl_gk_posts');

		// Get all data from tbl_gk_posts
		$respage = $this->Tbl_gk_posts->find("all", [
			'order' => ['id' => 'DESC']
		])->enableHydration(false); // Array format

		$cmspage = $respage->toArray();

		// CSV Export headers
		$filename = "gk_post_list_" . date("Y-m-d_H-i-s") . ".csv";
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		$output = fopen("php://output", "w");

		// CSV Header Row (add or remove fields as needed)
		fputcsv($output, ['ID', 'Post ID', 'Title', 'Post URL', 'Pincode', 'State', 'District', 'Hobbies', 'Description', 'Created', 'Updated']);

		// Loop through data and write to CSV
		foreach ($cmspage as $row) {
			fputcsv($output, [
				$row['id'],
				$row['post_id'],
				$row['title'],
				$row['post_url'],
				$row['pincode'],
				$row['state'],
				$row['district'],
				$row['hobbies'],
				$row['description'],
				date('Y-m-d H:i:s', strtotime($row['created'])),
				date('Y-m-d H:i:s', strtotime($row['updated'])),
			]);
		}

		fclose($output);
		exit;
	}

	public function exportstudentList3()
	{
		$this->loadModel('Tbl_exam_posts');

		// Get all data from tbl_exam_posts
		$respage = $this->Tbl_exam_posts->find("all", [
			'order' => ['id' => 'DESC']
		])->enableHydration(false); // Array format

		$cmspage = $respage->toArray();

		// CSV Export headers
		$filename = "exam_post_list_" . date("Y-m-d_H-i-s") . ".csv";
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		$output = fopen("php://output", "w");

		// CSV Header Row (fields based on your table structure)
		fputcsv($output, ['ID', 'Post ID', 'Post URL', 'Exam Name', 'Eligibility', 'Description']);

		// Loop through data and write to CSV
		foreach ($cmspage as $row) {
			fputcsv($output, [
				$row['id'],
				$row['post_id'],

				$row['post_url'],
				$row['exam_name'],       // <-- Example field
				$row['eligibility'],     // <-- Example field
				$row['description'],
				date('Y-m-d H:i:s', strtotime($row['created'])),
				date('Y-m-d H:i:s', strtotime($row['updated'])),
			]);
		}

		fclose($output);
		exit;
	}

	public function reportstudentList()
	{

		$this->loadModel('Tbl_faculty_members');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'college_id' => $collegeid, 'new_request' => 0)), array('order' => array('id' => 'DESC')));



		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'new_request' => 0)), array('order' => array('id' => 'DESC')));

		}

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}
	public function reportstudentList1()
	{
		$this->loadModel('Tbl_personality_post_answers');
		$this->loadModel('Tbl_faculty_members');
		$this->loadModel('Tbl_schools');
		$this->loadModel('Tbl_personality_posts');

		// Step 1: Get all answers
		$query = $this->Tbl_personality_post_answers->find()
			->order(['id' => 'DESC']);

		$personalityPosts = $query->toArray();

		// Step 2: Collect all unique std_ids and post_ids
		$stdIds = array_unique(array_column($personalityPosts, 'std_id'));
		$postIds = array_unique(array_column($personalityPosts, 'post_id'));

		// Step 3: Get faculty data (std_id → name, college_id)
		$facultyData = $this->Tbl_faculty_members->find()
			->where(['id IN' => $stdIds])
			->all()
			->combine('id', function ($row) {
				return [
					'name' => $row->name,
					'college_id' => $row->college_id
				];
			})
			->toArray();

		// Step 4: Get school name (college_id → school_name)
		$collegeIds = array_unique(array_column($facultyData, 'college_id'));
		$schoolList = $this->Tbl_schools->find('list', [
			'keyField' => 'id',
			'valueField' => 'name'
		])
			->where(['id IN' => $collegeIds])
			->toArray();

		// Step 5: Get personality post data (post_id → name, image, yesis, nois)
		$personalityData = $this->Tbl_personality_posts->find()
			->where(['post_id IN' => $postIds])
			->all()
			->combine('post_id', function ($row) {
				return [
					'name' => $row->name,
					'image' => $row->image,
					'yesis' => $row->yesis,
					'nois' => $row->nois
				];
			})
			->toArray();

		// Step 6: Merge all data
		foreach ($personalityPosts as &$post) {
			$stdId = $post->std_id;
			$postId = $post->post_id;

			// Student name and college
			$std_name = $facultyData[$stdId]['name'] ?? 'N/A';
			$college_id = $facultyData[$stdId]['college_id'] ?? null;
			$school_name = $schoolList[$college_id] ?? 'N/A';

			// Personality post details
			$pData = $personalityData[$postId] ?? ['name' => 'N/A', 'image' => null, 'yesis' => null, 'nois' => null];

			// Add to result
			$post->std_name = $std_name;
			$post->school_name = $school_name;
			$post->question_name = $pData['name'];
			$post->question_image = $pData['image'];
			$post->question_yesis = $pData['yesis'];
			$post->question_nois = $pData['nois'];
		}



		// Optional for view
		$this->set(compact('personalityPosts'));
	}



	public function exportpersonalityReport()
	{
		$this->loadModel('Tbl_personality_post_answers');
		$this->loadModel('Tbl_faculty_members');
		$this->loadModel('Tbl_schools');
		$this->loadModel('Tbl_personality_posts');

		$answers = $this->Tbl_personality_post_answers->find('all', [
			'order' => ['Tbl_personality_post_answers.id' => 'DESC']
		])->toArray();

		$stdIds = array_unique(array_column($answers, 'std_id'));
		$postIds = array_unique(array_column($answers, 'post_id'));

		$facultyData = $this->Tbl_faculty_members->find('all', [
			'conditions' => ['id IN' => $stdIds]
		])->combine('id', function ($row) {
			return [
				'name' => $row->name,
				'college_id' => $row->college_id
			];
		})->toArray();

		$collegeIds = array_unique(array_column($facultyData, 'college_id'));

		$schoolList = $this->Tbl_schools->find('list', [
			'keyField' => 'id',
			'valueField' => 'name'
		])->where(['id IN' => $collegeIds])->toArray();

		$personalityData = $this->Tbl_personality_posts->find('all', [
			'conditions' => ['post_id IN' => $postIds]
		])->combine('post_id', function ($row) {
			return [
				'name' => $row->name,
				'yesis' => $row->yesis,
				'nois' => $row->nois
			];
		})->toArray();

		$scoreMap = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5];

		$filename = "Personality_Report_" . date("Y-m-d") . ".csv";
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		$output = fopen("php://output", "w");

		fputcsv($output, ['Student Name', 'College Name', 'Question', 'Answer', 'Yes Is', 'No Is']);

		foreach ($answers as $post) {
			$stdId = $post->std_id;
			$postId = $post->post_id;

			$stdName = $facultyData[$stdId]['name'] ?? 'N/A';
			$collegeId = $facultyData[$stdId]['college_id'] ?? null;
			$collegeName = $schoolList[$collegeId] ?? 'N/A';

			$question = $personalityData[$postId]['name'] ?? 'N/A';
			$yesis = $personalityData[$postId]['yesis'] ?? '';
			$nois = $personalityData[$postId]['nois'] ?? '';

			$score = $scoreMap[$post->answer] ?? 'N/A';

			fputcsv($output, [$stdName, $collegeName, $question, $score, $yesis, $nois]);
		}

		fclose($output);
		exit;
	}

	public function exportstudentloginHistory()
	{
		$this->autoRender = false;
		$this->loadModel('Tbl_faculty_members');
		$this->loadModel('Tbl_schools');
		$this->loadModel('Tbl_courses'); // ✅ load course model

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");
		$key = $_GET['search'] ?? '';

		$collegeids = [];

		if (!empty($collegeid)) {
			$collegeids[] = $collegeid;
		} else {
			if (!empty($key)) {
				$respage = $this->Tbl_schools->find("all", [
					'conditions' => [
						'status' => 1,
						'OR' => [
							'name LIKE' => '%' . $key . '%',
							'state LIKE' => '%' . $key . '%',
							'district LIKE' => '%' . $key . '%',
							'city LIKE' => '%' . $key . '%',
						]
					]
				]);
				$respage->hydrate(false);
				$cmsp = $respage->toArray();

				if (!empty($cmsp)) {
					foreach ($cmsp as $cmspages) {
						$collegeids[] = $cmspages['id'];
					}
				}
			}
		}

		// Main query
		$query = $this->Tbl_faculty_members->find('all', [
			'conditions' => [
				'profileType IN' => ['3', '4'],
				'new_request' => 0
			],
			'order' => ['GREATEST(IFNULL(mobldate, 0), IFNULL(webldate, 0)) DESC', 'id DESC']
		]);

		if (!empty($key)) {
			$query->andWhere(function ($exp, $q) use ($key, $collegeids) {
				$orConditions = [
					'name LIKE' => '%' . $key . '%',
					'email LIKE' => '%' . $key . '%',
					'mobile LIKE' => '%' . $key . '%',
					'mobldate LIKE' => '%' . $key . '%',
					'webldate LIKE' => '%' . $key . '%',
				];

				if (!empty($collegeids)) {
					$orConditions['college_id IN'] = $collegeids;
				}

				return $exp->or_($orConditions);
			});
		}

		$data = $query->hydrate(false)->toArray();

		// Get unique college IDs
		$collegeIdsUsed = array_unique(array_column($data, 'college_id'));

		// Fetch school details
		$schoolData = $this->Tbl_schools->find('all', [
			'conditions' => ['id IN' => $collegeIdsUsed],
			'fields' => ['id', 'name', 'state', 'district', 'city']
		])->hydrate(false)->toArray();

		$schoolMap = [];
		foreach ($schoolData as $school) {
			$schoolMap[$school['id']] = $school;
		}

		// ✅ Fetch courses from tbl_courses based on college_id
		$courseData = $this->Tbl_courses->find('all', [
			'conditions' => ['college_id IN' => $collegeIdsUsed],
			'fields' => ['college_id', 'name']
		])->hydrate(false)->toArray();

		$courseMap = [];
		foreach ($courseData as $course) {
			// If multiple courses per college, this takes the first found
			if (!isset($courseMap[$course['college_id']])) {
				$courseMap[$course['college_id']] = $course['name'];
			}
		}

		// Send CSV Headers
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment;filename="student_login_history.csv"');

		$output = fopen('php://output', 'w');

		// CSV Header Row
		fputcsv($output, ['Name', 'Email', 'Mobile', 'Mobile Login Date', 'Web Login Date', 'Course Name', 'College Name', 'State', 'District', 'City']);

		// CSV Data Rows
		foreach ($data as $row) {
			$college_id = $row['college_id'];
			$school = $schoolMap[$college_id] ?? [];

			$courseName = $courseMap[$college_id] ?? ''; // ✅ new course name
			$schoolName = $school['name'] ?? '';
			$state = $school['state'] ?? '';
			$district = $school['district'] ?? '';
			$city = $school['city'] ?? '';

			fputcsv($output, [
				$row['name'],
				$row['email'],
				$row['mobile'],

				$row['mobldate'],
				$row['webldate'],
				$courseName,
				$schoolName,
				$state,
				$district,
				$city
			]);
		}

		fclose($output);
		exit;
	}


	public function exportstudentReport()
	{

		$this->loadModel('Tbl_faculty_members');

		$Admincheckid = $this->request->session()->read("Admincheck.id");

		$collegeid = $this->request->session()->read("Tbl_faculty_members.collegeid");

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		if (!empty($collegeid)) {



			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'college_id' => $collegeid, 'new_request' => 0)), array('order' => array('id' => 'DESC')));



		} else {

			$respage = $this->Tbl_faculty_members->find("all", array('conditions' => array('profileType IN' => array('3', '4'), 'new_request' => 0)), array('order' => array('id' => 'DESC')));

		}

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('viewData', $cmspage);

	}





	public function freelancerJoblist()
	{

		$membid = $this->request->session()->read("Tbl_faculty_members.id");

		$conn = ConnectionManager::get('default');

		$stmt = $conn->execute('select tbl_shortlist_freelancers.*,tbl_company_accs.name,tbl_company_accs.mobile,tbl_company_accs.email from tbl_shortlist_freelancers LEFT JOIN  tbl_company_accs ON tbl_shortlist_freelancers.company_id = tbl_company_accs.id  where tbl_shortlist_freelancers.student_id =' . $membid);

		$result = $stmt->fetchAll('assoc');

		$this->set('viewData', $result);

	}

	public function freelancershortlistedList()
	{

		$company_id = $this->request->session()->read("company_accs.id");

		$conn = ConnectionManager::get('default');

		//	$stmt = $conn->execute('select tbl_student_shortlisted_profiles.sid,tbl_student_shortlisted_profiles.pro_status,tbl_student_shortlisted_profiles.profile_name,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_courses.name as course_name,tbl_schools.name as college_name from tbl_student_shortlisted_profiles LEFT JOIN  tbl_faculty_members ON tbl_student_shortlisted_profiles.sid = tbl_faculty_members.id  LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id where tbl_student_shortlisted_profiles.cmp_id ='.$company_id.' AND tbl_faculty_members.status=1');

		$stmt = $conn->execute('select tbl_shortlist_freelancers.*,tbl_faculty_members.name,tbl_faculty_members.mobile,tbl_faculty_members.email,tbl_schools.name as college_name,tbl_courses.name as course_name from tbl_shortlist_freelancers LEFT JOIN  tbl_faculty_members ON tbl_shortlist_freelancers.student_id = tbl_faculty_members.id  LEFT JOIN  tbl_member_details ON tbl_member_details.acc_id = tbl_faculty_members.id LEFT JOIN  tbl_schools ON tbl_schools.id = tbl_faculty_members.college_id  LEFT JOIN  tbl_courses ON tbl_member_details.course = tbl_courses.id where tbl_shortlist_freelancers.company_id =' . $company_id);

		$result = $stmt->fetchAll('assoc');

		$this->set('viewData', $result);

	}

	public function freelancerProfile()
	{



		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_shortlist_freelancers');

		$this->loadModel('Tbl_posts');

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

		}

		$company_id = $this->request->session()->read("company_accs.id");

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$this->set('viewDetails', $datast1);



		$resshortlist = $this->Tbl_shortlist_freelancers->find("all", array('conditions' => array('student_id' => $id, 'company_id' => $company_id)));

		$resshortlist->hydrate(false);

		$datshortlist = $resshortlist->first();

		$this->set('shortlistData', $datshortlist);



		//$respage =$this->Tbl_posts->find("all",array('conditions'=>array('status'=>1)),array('order'=>array('updated'=>'desc')));

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('company_id' => $company_id, 'status' => 1)), array('order' => array('updated' => 'desc')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('joblist', $cmspage);



		if ($this->request->is('post')) {

			$this->request->data['company_id'] = $company_id;

			$this->request->data['requested_at'] = date('Y-m-d');

			$datapost = $this->Tbl_shortlist_freelancers->newEntity($this->request->data);

			$this->Tbl_shortlist_freelancers->save($datapost);

			$this->Flash->success('Request successfully sent', array('key' => 'acc_alert'));

			return $this->redirect('freelancer?id=' . $this->request->data['student_id']);
			die;

		}
	}
	public function freelancerProfile1()
	{



		$this->viewBuilder()->layout('member');

		$this->loadModel('Tbl_faculty_members');

		$this->loadModel('Tbl_member_details');

		$this->loadModel('Tbl_shortlist_freelancers');

		$this->loadModel('Tbl_posts');

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

		}

		$company_id = $this->request->session()->read("company_accs.id");

		$restd = $this->Tbl_faculty_members->find("all", array('conditions' => array('id' => $id)));

		$restd->hydrate(false);

		$datast = $restd->first();

		$this->set('viewData', $datast);



		$restd1 = $this->Tbl_member_details->find("all", array('conditions' => array('acc_id' => $id)));

		$restd1->hydrate(false);

		$datast1 = $restd1->first();

		$this->set('viewDetails', $datast1);



		$resshortlist = $this->Tbl_shortlist_freelancers->find("all", array('conditions' => array('student_id' => $id, 'company_id' => $company_id)));

		$resshortlist->hydrate(false);

		$datshortlist = $resshortlist->first();

		$this->set('shortlistData', $datshortlist);



		//$respage =$this->Tbl_posts->find("all",array('conditions'=>array('status'=>1)),array('order'=>array('updated'=>'desc')));

		$respage = $this->Tbl_posts->find("all", array('conditions' => array('company_id' => $company_id, 'status' => 1)), array('order' => array('updated' => 'desc')));

		$respage->hydrate(false);

		$cmspage = $respage->toArray();

		$this->set('joblist', $cmspage);



		if ($this->request->is('post')) {

			$this->request->data['company_id'] = $company_id;

			$this->request->data['requested_at'] = date('Y-m-d');

			$datapost = $this->Tbl_shortlist_freelancers->newEntity($this->request->data);

			$this->Tbl_shortlist_freelancers->save($datapost);

			$this->Flash->success('Request successfully sent', array('key' => 'acc_alert'));

			return $this->redirect('freelancer?id=' . $this->request->data['student_id']);
			die;

		}

	}


}



?>