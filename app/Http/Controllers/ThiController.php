<?php
namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\Question;
use JP_COMMUNITY\Models\UserExam;
use Illuminate\Common\Pdf;
class ThiController extends BaseController 
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function viewresult() 
    {
        
        $row = DB::select("SELECT * FROM user_exam WHERE user_id=" . $this->getUserId() . " ORDER BY exam_date DESC LIMIT 1");
        if (!is_array($row) || count($row) == 0) {
            return redirect()->action('ThiController@index');
        }
        $row=$row[0];
        $html = UserExam::getHtmlForExamResult($row['id'], $title_header);

        $date = explode(' ', $row['exam_date']);
        $date = explode('-', $date[0]);
        Pdf::createFilePdf(Pdf::DOWNLOAD, $html, $date[0] . '_' . $date[1] . '_' . $date[2] . '.pdf', $title_header);
    }

    public function index(Request $request) 
    {

        $i = intval(date('i'));
        $h = intval(date('H'));
        $this->setParams($request, $nganhNgheId, $level, $questionIds, $questions, $nganhNghes, $showFormNganhNgheCapBac);
        $this->setupExamingSession($request, $nganhNgheId, $level, $questionIds);
        if ($this->isReExam()) {
            $this->processReExam($request,$miniutes);
        } else {
            $this->processExam($request,$eh,$em,$miniutes,$message);
        }

        $success = request()->session()->get('success');
        
        return view('thi.index', compact(['i', 'h', 'success', 'nganhNgheId', 'level','questions','nganhNghes','showFormNganhNgheCapBac','eh','em','miniutes','message']));
    }
    
    private function saveDB(Request $request) 
    {
        $date = date('Y-m-d');
        $h = $request->get('h');
        $m = $request->get('i');
        if($m==0){
            $m=59;
            $h--;
        }
        else{
            $m--;
        }
        
        $row = DB::select("select * from user_exam where DATE(exam_date)='" . $date . "' AND user_id=" . $this->getUserId());
        if (is_array($row) && count($row) > 0) {
            return;
        }

        DB::beginTransaction();
        try {
            
            $identity = Session::get('user');  
            $sh = $identity['sh'];
            $sm = $identity['sm'];
            
            $userExamId = DB::table('user_exam')->insertGetId(
                    array(
                        'user_id' => $this->getUserId(),
                        'nganh_nghe_id' => $request->get('nganh_nghe_id_form2'),
                        'level' => $request->get('level_form2'),
                        'exam_date' => date("Y-m-d $h:$m:s"),
                        'sh' => $sh,
                        'sm' => $sm,
                        'eh' => $h,
                        'em' => $m,
                        'es'=> rand(1, 59),
                    )
            );
            $i = 0;
            $questionIds = $request->get('question_id');
            $answerIds = $request->get('answer_id');
            $answerSigns = $request->get('answer_sign');
            $dapanSigns = $request->get('dapan_sign');
            $answersJsons = $request->get('answers_json');
            $count_correct = 0;
            
            for ($i = 0, $n = count($questionIds); $i < $n; $i++) {
                if ($answerSigns[$i] == $dapanSigns[$i]) {
                    $is_correct = 1;
                    $count_correct++;
                } else {
                    $is_correct = 0;
                }

                DB::table('user_exam_detail')->insert(array(
                    'user_exam_id' => $userExamId,
                    'question_id' => $questionIds[$i],
                    'answer_id' => ($answerIds[$i] == '' ? '-1' : $answerIds[$i]),
                    'is_correct' => $is_correct,
                    'answer_sign' => $answerSigns[$i]=='Z'?' ':$answerSigns[$i],
                    'dapan_sign' => $dapanSigns[$i],
                    'answers_json' => $answersJsons[$i],
                ));
            }

            $config_exam =DB::table('config_exam')->first();

            if ($count_correct >= $config_exam['phan_tram'] * count($questionIds)) {
                DB::table('user_pass')->insert(array(
                    'user_id' => $this->getUserId(),
                    'nganh_nghe_id' => $request->get('nganh_nghe_id_form2'),
                    'level' => $request->get('level_form2'),
                    'user_exam_id' => $userExamId,
                ));
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    private function saveDBAgain(Request $request) 
    {
        
        DB::beginTransaction();

        try {
            $user_exam = DB::select("select * from user_exam where user_id=" . $this->getUserId() . ' ORDER BY exam_date DESC LIMIT 1');
            if (is_array($user_exam) && count($user_exam) > 0) {
                $userExamId = $user_exam[0]['id'];
            } else {
                $userExamId = -1;
            }
            $i = 0;
            
            $questionIds = $request->get('question_id');
            $answerIds = $request->get('answer_id');
            $answerSigns = $request->get('answer_sign');
            $dapanSigns = $request->get('dapan_sign');
            $answersJsons = $request->get('answers_json');
            $count_correct = 0;
            
            DB::table('user_exam_detail')->where('user_exam_id=' . $userExamId)->delete();
            for ($i = 0, $n = count($questionIds); $i < $n; $i++) {
                if ($answerSigns[$i] == $dapanSigns[$i]) {
                    $is_correct = 1;
                    $count_correct++;
                } else {
                    $is_correct = 0;
                }

                DB::table('user_exam_detail')->insert(array(
                    'user_exam_id' => $userExamId,
                    'question_id' => $questionIds[$i],
                    'answer_id' => ($answerIds[$i] == '' ? '-1' : $answerIds[$i]),
                    'is_correct' => $is_correct,
                    'answer_sign' => $answerSigns[$i]=='Z'?' ':$answerSigns[$i],
                    'dapan_sign' => $dapanSigns[$i],
                    'answers_json' => $answersJsons[$i],
                ));
            }

            

            $config_exam =DB::table('config_exam')->first();

            if ($count_correct >= $config_exam['phan_tram'] * count($questionIds)) {
                DB::table('user_pass')->insert(array(
                    'user_id' => $this->getUserId(),
                    'nganh_nghe_id' => $request->get('nganh_nghe_id_form2'),
                    'level' => $request->get('level_form2'),
                    'user_exam_id' => $userExamId,
                ));
                $allow_re_exam = 0;
            } else {
                $allow_re_exam = 1;
            }
            DB::table('user_exam')->where('id=' . $userExamId)->update(array('allow_re_exam' => $allow_re_exam, 'nganh_nghe_id' => $request->get('nganh_nghe_id_form2'), 'level' => $request->get('level_form2')));
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    
    private function submitReExam(Request $request) 
    {
        $this->saveDBAgain($request);
        $this->resetSession();
        request()->session()->flash('success', 'Chúc mừng bạn đã hoàn thành kỳ thi lần này.');
        return redirect()->action('ThiController@index');
        exit;
    }

    private function submitExam(Request $request) 
    {
        $this->saveDB($request);
        $this->resetSession();
        request()->session()->flash('success', 'Chúc mừng bạn đã hoàn thành kỳ thi lần này.');
        return redirect()->action('ThiController@index');
        exit;
    }

    /**
     * bật session đang thi
     * @param int|string $nganhNgheId
     * @param int|string $level
     * @param array $questionIds
     * @param array $identity
     */
    private function turnOnExamingSession($nganhNgheId, $level, $questionIds, &$identity) 
    {
        $identity['examing'] = true;
        $identity['nganh_nghe_id'] = $nganhNgheId;
        $identity['level'] = $level;
        $identity['questionIds'] = $questionIds;
        $identity['sh'] = date('H');
        $identity['sm'] = date('i');
    }

    /**
     * kiểm tra thử user hiện tại có phải là đang được phép thi lại hay không
     * @return boolean
     */
    private function isReExam() 
    {
        
        $user_exam = DB::select("select * from user_exam where user_id=" . $this->getUserId() . ' ORDER BY exam_date DESC LIMIT 1');
        if (is_array($user_exam) && count($user_exam) > 0 && $user_exam[0]['allow_re_exam'] == '1') {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     * @param array $data
     * @param int|string $nganhNgheId
     * @param int|string $level
     * @param array $questionIds
     * @param array $questions
     * @param array $nganhNghes
     * @param bool $showFormNganhNgheCapBac
     */
    private function setParams(Request $request, &$nganhNgheId, &$level, &$questionIds, &$questions, &$nganhNghes, &$showFormNganhNgheCapBac) 
    {
        
        $identity = Session::get('user');  
        
        if (isset($identity['examing']) && $identity['examing'] == true) {
            $nganhNgheId = $identity['nganh_nghe_id'];
            $level = $identity['level'];
            $questionIds = $identity['questionIds'];
        } else {
            $nganhNgheId = ($request->get('nganh_nghe_id')!=null) ? $request->get('nganh_nghe_id') : 0;
            $level = ($request->get('level')!=null) ? $request->get('level') : 0;            
            $config_exam =DB::table('config_exam')->first();
            $questionIds = Question::getQuestionIdsByLevelAndNganhNgheId($nganhNgheId, $level, $config_exam['number']);
        }
        
        $date = date('Y-m-d');
        $h = $request->get('h', date('H'));
        $m = $request->get('i', date('i'));
        $exam_time = DB::select("select DATE(`date`) AS date,sh,sm,eh,em from exam_time where DATE(`date`)='$date' AND ($h>sh OR ($h=sh AND $m>=sm)) AND ($h < eh OR ($h=eh AND $m<=em))");
        if (is_array($exam_time) && count($exam_time) > 0) {
            $user_exam = DB::select("select * from user_exam where DATE(exam_date)='" . $exam_time[0]['date'] . "' AND user_id=" . $this->getUserId());
        } else {
            $user_exam = array();
        }

        if (
                ($request->has('_token') == false && !isset($identity['examing'])) 
                || (!is_array($exam_time) || count($exam_time) == 0)//nằm ngoài thời gian thi
                || (is_array($user_exam) && count($user_exam) > 0 && $user_exam[0]['allow_re_exam'] != '1')//đã thi rồi
        ) {
            $questions = array();
        } else {
            $questions = Question::getQuestionsByQuestionIds($questionIds);
        }

        if (
                ($request->get('question_id')!=null)//nhấn nút hoàn tất
                || (!is_array($exam_time) || count($exam_time) == 0)//nằm ngoài thời gian thi
                || (is_array($user_exam) && count($user_exam) > 0 && $user_exam[0]['allow_re_exam'] != '1')//đã thi rồi
        ) {
            $nganhNghes = array();
        } else {
            $nganhNghes = DB::select('SELECT * FROM nganh_nghe');
        }

        if (
                $request->has('_token')
                || (!is_array($exam_time) || count($exam_time) == 0)//nằm ngoài thời gian thi
                || (is_array($user_exam) && count($user_exam) > 0 && $user_exam[0]['allow_re_exam'] != '1')//đã thi rồi
        ) {
            $showFormNganhNgheCapBac = FALSE;
        } else {
            if (isset($identity['examing']) && $identity['examing'] == true) {
                $showFormNganhNgheCapBac = FALSE;
            } else {
                $showFormNganhNgheCapBac = true;
            }
        }
    }

    /**
     * nếu session đang thi chưa được bật thi sẽ bật
     * @param array $data
     * @param int|string $nganhNgheId
     * @param int|string $level
     * @param array $questionIds
     */
    private function setupExamingSession(Request $request, $nganhNgheId, $level, $questionIds) 
    {
        if ($request->has('_token')) {
            
            $identity = Session::get('user');  
            if (!isset($identity['examing']) || $identity['examing'] == FALSE) {                
                $this->turnOnExamingSession($nganhNgheId, $level, $questionIds, $identity);
                Session::set('user',$identity);    
            }
        }
    }

    private function processReExam(Request $request,&$miniutes) 
    {
        if ($request->has('_token')) {
            if ($request->get('question_id')) {
                $this->submitReExam($request);
                exit;
            }
        }
        $miniutes = 0;
    }

    private function processExam(Request $request,&$eh,&$em,&$miniutes,&$message) 
    {
        
        $date = date('Y-m-d');
        $h = $request->get('h', date('H'));
        $m = $request->get('i', date('i'));
        $row = DB::select("select DATE(`date`) AS date,sh,sm,eh,em from exam_time where DATE(`date`)='$date' AND ($h>sh OR ($h=sh AND $m>=sm)) AND ($h < eh OR ($h=eh AND $m<=em))");
        if (is_array($row) && count($row) > 0) {
            $row=$row[0];
            $start = new \DateTime($row['date'] . ' ' . $row['sh'] . ':' . $row['sm'] . ':00');
            $current = new \DateTime(date('Y-m-d H:i:00'));
            $diff = $current->diff($start);
            $eh = $row['eh'];
            $em = $row['em'];
        }
        if ((!is_array($row) || count($row) == 0) && ($request->has('_token') == false || ($request->has('_token')&& $request->get('question_id')==null))) {
            $miniutes = 0;
            $message = 'Thời điểm này không nằm trong thời gian thi hoặc bạn đã hết giờ thi.';
        } else {
            $row = DB::select("select * from user_exam where DATE(exam_date)='" . $row['date'] . "' AND user_id=" . $this->getUserId());
            if (is_array($row) && count($row) > 0) {
                $miniutes = 0;
                $message = '';
            } else {
                if ($request->has('_token')) {
                    if ($request->get('question_id')) {
                        $this->submitExam($request);
                        exit;
                    }
                }
                $miniutes = $diff->h * 60 + $diff->i;
            }
        }
    }

}
