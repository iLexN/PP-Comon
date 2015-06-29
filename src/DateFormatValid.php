<?php
namespace PP\Common;


/**
 * Description of DateFormatValid
 
 */
class DateFormatValid {
    public static function isDateFormatValid($Date)
    {
        $cnt = strlen($Date);
        $arr = array();
        if ($cnt > 5 && $cnt < 8) {
            if (preg_match_all('/(\d{1,2})\/(\d{4})/', $Date, $arr)===false) {
                return false;
            }
            if (intval($arr[1][0]) < 1 || intval($arr[1][0]) > 12) {
                return false;
            } else {
                return true;
            }
        } elseif ($cnt > 7 && $cnt < 11) {
            if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $Date, $arr)===false) {
                return false;
            }
            if (intval($arr[1][0]) < 1 || intval($arr[1][0]) > 31) {
                return false;
            }
            if (intval($arr[2][0]) < 1 || intval($arr[2][0]) > 12) {
                return false;
            }
            if (checkdate(intval($arr[2][0]), intval($arr[1][0]), intval($arr[3][0]))) {
                return true;
            }
        }
        return false;
    }
    public static function isDateBefore($Date, $RefDate=null)
    {
        $arr = array();
        if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $Date, $arr)===false) {
            return false;
        }
        $t1 = new \DateTime();
        $t1->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        $t1->setTime(0, 0, 0);
        $t2 = new \DateTime();
        if ($RefDate!=null) {
            if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $RefDate, $arr)===false) {
                return false;
            }
            $t2->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        }
        $t2->setTime(0, 0, 0);
        return $t1<$t2;
    }
    public static function isDateAfter($Date, $RefDate=null)
    {
        $arr = array();
        if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $Date, $arr)===false) {
            return false;
        }
        $t1 = new \DateTime();
        $t1->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        $t1->setTime(0, 0, 0);
        $t2 = new \DateTime();
        if ($RefDate!=null) {
            if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $RefDate, $arr)===false) {
                return false;
            }
            $t2->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        }
        $t2->setTime(0, 0, 0);
        return $t1>$t2;
    }
    public static function isDateEqual($Date, $RefDate=null)
    {
        $arr = array();
        if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $Date, $arr)===false) {
            return false;
        }
        $t1 = new \DateTime();
        $t1->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        $t1->setTime(0, 0, 0);
        $t2 = new \DateTime();
        if ($RefDate!=null) {
            if (preg_match_all('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $RefDate, $arr)===false) {
                return false;
            }
            $t2->setDate(intval($arr[3][0]), intval($arr[2][0]), intval($arr[1][0]));
        }
        $t2->setTime(0, 0, 0);
        return $t1 == $t2;
    }
    public static function isEmailValid($Email)
    {
        if (filter_var($Email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        } else {
            return true;
        }
    }
    public static function isLuhnValid($CardNo)
    {
        if (!is_string($CardNo)) {
            return false;
        }
        if (preg_match('/^[0-9]{16}$/', $CardNo)!=1) {
            return false;
        }

        $reverseCardNo = '';
        for ($i=15; $i>=0; $i--) {
            $reverseCardNo .= substr($CardNo, $i, 1);
        }
        
        $sum = 0;
        for ($i=0;$i<16;$i++) {
            if ($i%2==0) {
                $sum += intval(substr($reverseCardNo, $i, 1));
            } else {
                switch (intval(substr($reverseCardNo, $i, 1))) {
                    case 5:
                        $sum += 1;
                        break;
                    case 6:
                        $sum += 3;
                        break;
                    case 7:
                        $sum += 5;
                        break;
                    case 8:
                        $sum += 7;
                        break;
                    case 9:
                        $sum += 9;
                        break;
                    default:
                        $sum += intval(substr($reverseCardNo, $i, 1))*2;
                }
            }
        }
        return ($sum%10==0);
    }
}
