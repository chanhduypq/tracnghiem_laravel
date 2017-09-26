<?php

namespace JP_COMMUNITY\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Pdfresult;
use JP_COMMUNITY\Models\Headerpdf;

class UserExam extends BaseModel
{
    protected $table = 'user_exam';
    
    public static function getHtmlForExamResult($user_exam_id, &$title_header) {
        $row = DB::select("select sh,sm,eh,em,es,user_exam_detail.question_id,user_pass.id AS user_pass_id,"
                . "user_exam.nganh_nghe_id,"
                . "user_exam.level,"
                . "DATE_FORMAT(user_exam.exam_date,'%d/%m/%Y') AS date,"
                . "DATE_FORMAT(user_exam.exam_date,'%Y') AS year,"
                . "user.danh_xung,"
                . "user.full_name,"
                . "user_exam_detail.is_correct,"
                . "user_exam_detail.dapan_sign,"
                . "user_exam_detail.answer_sign,"
                . "user_exam_detail.answer_id,"
                . "user_exam_detail.answers_json,"
                . "question.content AS question_content,"
                . "nganh_nghe.title "
                . "from user_exam "
                . "JOIN user_exam_detail ON user_exam.id=user_exam_detail.user_exam_id "
                . "JOIN user ON user.id=user_exam.user_id "
                . "JOIN nganh_nghe ON nganh_nghe.id=user_exam.nganh_nghe_id "
                . "JOIN question ON question.id=user_exam_detail.question_id "
                . "LEFT JOIN user_pass ON user_pass.user_exam_id=user_exam.id "
                . "WHERE user_exam.id=$user_exam_id ORDER BY user_exam_detail.id ASC");
        $count_correct = 0;
        $count_incorrect = 0;
        $questionIds = array();
        foreach ($row as $r) {
            if ($r['is_correct'] == '1') {
                $count_correct++;
            } else {
                $count_incorrect++;
            }
            $questionIds[] = $r['question_id'];
            $questions[$r['question_id']]['question_content'] = $r['question_content'];
            $answers_json= json_decode($r['answers_json'],TRUE);
            foreach ($answers_json as $key=>$value){
                $questions[$r['question_id']]['answers'][] = array('answer_sign' => $key, 'answer_content' => $value['content'], 'is_dap_an' => $value['is_dapan']);
            }
        }

        if (is_numeric($row[0]['user_pass_id'])) {
            $result = 'Đạt';
        } else {
            $result = 'Chưa đạt';
        }
        $diem = round($count_correct * 10 / count($row), 1);
        $questionsHtml = Pdfresult::getQuestionsHtml($questions);
               
        Pdfresult::setTime($startTime, $endTime, $during, $row[0]);

        $level = Pdfresult::getLevelHtml($row[0]['level']);
        $title_header = $row[0]['date'];
        $headers = json_decode(Headerpdf::getHeader(), TRUE);
        foreach ($headers as &$header) {
            $header = str_replace('{level}', $level, $header);
            $header = str_replace('{nam}', $row[0]['year'], $header);
        }
        
        $header = Pdfresult::getHeaderHtml($headers);
        $css = Pdfresult::getCss();
        Pdfresult::setHtmlForDetailResult($div1, $div2, $div3, $row);
        $detailResultHtml = Pdfresult::getDetailResultHtml($div1, $div2, $div3);
        $userInfoHtml = Pdfresult::getUserInfoHtml($row[0]['full_name'], $row[0]['title'], $row[0]['date'], $startTime, $endTime, $during);
        $html = '<style>
                  ' . $css . '
                </style>
                <body>
                ' . $header . '
                <div>&nbsp;</div>
                <table style="width: 100%;">
                    '.$userInfoHtml.'
                </table>
                <div>&nbsp;</div>
                <table style="width: 100%;">
                    ' . $detailResultHtml . '
                </table>
                
                <div>&nbsp;</div>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 1%;">&nbsp;</td>
                            <td style="width: 98%;text-align: left;border: 2px solid #cccccc;">
                                <div>&nbsp;</div>
                                &nbsp;&nbsp;Số câu đúng:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $count_correct . '<br>
                                Điểm kiểm tra:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $diem . '<br>
                                Kết quả kiểm tra:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $result . '<br>
                            </td>
                            <td style="width: 1%;">&nbsp;</td>
                            
                        </tr>                       
                        
                    </tbody>
                </table>
                <div>&nbsp;</div>
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                            <td colspan="3" style="width: 100%;text-align: center;font-size: 20px;">ĐÁP ÁN CHI TIẾT</td>
                            
                            
                        </tr>  
                        <tr>
                            <td style="width: 1%;">&nbsp;</td>
                            <td style="width: 98%;text-align: left;border: 2px solid #666666;">
                                ' . $questionsHtml . '
                            </td>
                            <td style="width: 1%;">&nbsp;</td>
                            
                        </tr>                       
                        
                    </tbody>
                </table>
                
                </body>
                ';
        return $html;
    }
}