<?php 
namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\Question;
use JP_COMMUNITY\Models\UserExam;
use JP_COMMUNITY\Models\Pdf;
use JP_COMMUNITY\Models\Numeric;

class QuestionController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    

    public function index($nganhNgheId,$level) 
    {

        if (Numeric::isInteger($level) === FALSE) {
            $level = 1;
        }
        if (Numeric::isInteger($nganhNgheId) === FALSE) {
            $nganhNgheId = 0;
        }

        $questionArray = Question::getQuestionsByLevelAndNganhNgheIdForPageQuestion($nganhNgheId, $level);
        
        return view('question.index', compact(['questionArray']));
        
    }

    
}
