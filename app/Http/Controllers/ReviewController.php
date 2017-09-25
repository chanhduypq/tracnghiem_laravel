<?php 
namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\Question;

class ReviewController extends BaseController {


    private function saveDB(Request $request) {
        DB::beginTransaction();
        try {
            /**
             *  xóa thông tin lần ôn tập trước
             */
            DB::statement('DELETE FROM user_review_detail WHERE user_review_id IN (SELECT id FROM user_review WHERE user_id='.$this->getUserId().')');
            DB::table('user_review')->where('user_id', $this->getUserId())->delete();
            $identity=Session::get('user');
            $sh = $identity['sh_review'];
            $sm = $identity['sm_review'];

            $h = intval(date('H'));
            $m = intval(date('i'));

            /**
             * insert table user_review
             */            
            $userExamId=DB::table('user_review')->insertGetId(
                    array(
                        'user_id' => $this->getUserId(),
                        'nganh_nghe_id' => $request->get('nganh_nghe_id_form2'),
                        'level' => $request->get('level_form2'),
                        'review_date' => date("Y-m-d $h:$m:s"),
                        'sh' => $sh,
                        'sm' => $sm,
                        'eh' => $h,
                        'em' => $m,
                        'es' => rand(1, 59),
                    )
            );
            
            /**
             * insert table user_review_detail
             */
            $i = 0;
            $questionIds = $data['question_id'];
            $answerIds = $data['answer_id'];
            $answerSigns = $data['answer_sign'];
            $dapanSigns = $data['dapan_sign'];
            $answersJsons = $data['answers_json'];
            $count_correct = 0;
            
            for ($i = 0, $n = count($questionIds); $i < $n; $i++) {
                if ($answerSigns[$i] == $dapanSigns[$i]) {
                    $is_correct = 1;
                    $count_correct++;
                } else {
                    $is_correct = 0;
                }
                DB::table('user_review_detail')->insert(array(
                    'user_review_id' => $userExamId,
                    'question_id' => $questionIds[$i],
                    'answer_id' => ($answerIds[$i] == '' ? '-1' : $answerIds[$i]),
                    'is_correct' => $is_correct,
                    'answer_sign' => $answerSigns[$i] == 'Z' ? ' ' : $answerSigns[$i],
                    'dapan_sign' => $dapanSigns[$i],
                    'answers_json' => $answersJsons[$i],
                        )
                );
                
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function viewresultAction() {
        
        $row = DB::select("SELECT * FROM user_review WHERE user_id=" . $this->getUserId() . " ORDER BY review_date DESC LIMIT 1");
        if (!is_array($row) || count($row) == 0) {
            $this->_helper->redirector('index', 'review', 'default');
            return;
        }
        $row=$row[0];
//     tuetc   $html = Default_Model_Userreview::getHtmlForReviewResult($row['id'], $title_header);

        $date = explode(' ', $row['review_date']);
        $date = explode('-', $date[0]);
//    tuetc    Core_Common_Pdf::createFilePdf(Core_Common_Pdf::DOWNLOAD, $html, $date[0] . '_' . $date[1] . '_' . $date[2] . '.pdf', $title_header);
    }

    public function index(Request $request) {
        $identity=Session::get('user');
        if ($request->isMethod('POST')) {//submit
            if ($request->get('question_id')) {//trả lời câu hỏi xong và nhấn nút hoàn tất
                $this->saveDB($request);
                $this->resetSession();
                return redirect()->action('ReviewController@index');
            } else {//hệ thống đang ở trạng thái submit của việc [chọn ngành nghề, cấp bậc; sau đó nhấn nút bắt đầu]. Có thể vừa nhấn nút bắt đầu hoặc reload page

                if (isset($identity['examing_review']) && $identity['examing_review'] == true) {//reload page
                    $nganhNgheId = $identity['nganh_nghe_id_review'];
                    $level = $identity['level_review'];
                    $questionIds = $identity['questionIds_review'];
                } else {//mới vừa làm việc [chọn ngành nghề, cấp bậc; sau đó nhấn nút bắt đầu]
                    $nganhNgheId = $request->get('nganh_nghe_id');
                    $level = $request->get('level');
                    $config_exam =DB::table('config_exam')->first();
                    $questionIds = Question::getQuestionIdsByLevelAndNganhNgheId($nganhNgheId, $level, $config_exam['number']);
                }

                $newQuestions = Question::getQuestionsByQuestionIds($questionIds);
                if (!isset($identity['examing_review']) || $identity['examing_review'] == FALSE) {
                    $identity['examing_review'] = true;
                    $identity['time_start_review'] = time();
                    $identity['nganh_nghe_id_review'] = $nganhNgheId;
                    $identity['level_review'] = $level;
                    $identity['questionIds_review'] = $questionIds;
                    $identity['sh_review'] = date('H');
                    $identity['sm_review'] = date('i');
                }
                Session::set('user',$identity);
            }
        } else {//user vào page này bằng việc click trên menu

            if (isset($identity['examing_review']) && $identity['examing_review'] == true) {//[chọn ngành nghề, cấp bậc; sau đó nhấn nút bắt đầu], việc này đã được làm
                $level = $identity['level_review'];
                $nganhNgheId = $identity['nganh_nghe_id_review'];
                $questionIds = $identity['questionIds_review'];
                $newQuestions = Question::getQuestionsByQuestionIds($questionIds);
            } else {//
                $nganhNgheId = $level = 0;
                $newQuestions = array();
            }
        }

        if (isset($identity['examing_review']) && $identity['examing_review'] == true) {
            $miniutes = (time() - $identity['time_start_review']) / 60;
            $miniutes = round($miniutes, 0);
        } else {
            $miniutes = 0;
        }
       
        $questions = $newQuestions;
        $nganhNghes =DB::select('SELECT * FROM nganh_nghe');
        
        
        
        return view('review.index', compact(['questions', 'nganhNghes','nganhNgheId','level','miniutes']));
    }

    
}
