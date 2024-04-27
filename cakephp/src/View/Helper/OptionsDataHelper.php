<?php
/* src/View/Helper/OptionsDataHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;

class OptionsDataHelper extends Helper
{   
    /***
     *
     *  $asDistrictList = district_list($state_id);
        $asDistrictOptionList = district_list_toOption($asDistrictList, $_SESSION['trainig_provider']['district_name']);
        <select name="location_search" id="country_id" class="form-select search-location training-provider-district">
            			' . $asDistrictOptionList . '
        </select>
     *
     */
    
    public function district_list_toOption($asDistrictlist = array(), $selectedDistrictId = 0) {
        $ssOptionList = "";
        foreach ($asDistrictlist as $snDistrictCode => $ssDistrictName) {
            $ssDistrictName = strtolower($ssDistrictName);
            $ssDistrictName = ucwords($ssDistrictName);

            if ($selectedDistrictId != "" && $selectedDistrictId == $snDistrictCode)
                $ssOptionList .= "<option value='$snDistrictCode' selected>$ssDistrictName</option>";
            else
                $ssOptionList .= "<option value='$snDistrictCode'>$ssDistrictName</option>";
        }
        return $ssOptionList;
    }

      public function city_list_toOption($asDistrictlist = array(), $selectedDistrictId = 0) {
        $ssOptionList = "";
        foreach ($asDistrictlist as $snDistrictCode => $ssDistrictName) {
            $ssDistrictName = strtolower($ssDistrictName);
            $ssDistrictName = ucwords($ssDistrictName);
            if ($selectedDistrictId != "" && $selectedDistrictId == $snDistrictCode)
                $ssOptionList .= "<option value='$snDistrictCode' selected>$ssDistrictName</option>";
            else
                $ssOptionList .= "<option value='$snDistrictCode'>$ssDistrictName</option>";
        }
        return $ssOptionList;
    }
    
    public function state_list_toOption($asStatelist = array(), $selectedStateId = "") {
        $ssOptionList = "";
        foreach ($asStatelist as $snStateCode => $ssStateName) {
            $ssStateName = strtolower($ssStateName);
            $ssStateName = ucwords($ssStateName);
            if ($selectedStateId != "" && $selectedStateId == $snStateCode) {
    
                $ssOptionList .= "<option value='$snStateCode' selected>$ssStateName</option>";
            } else {
                $ssOptionList .= "<option value='$snStateCode'>$ssStateName</option>";
            }
        }
        return $ssOptionList;
    }
    
    public function list_toOption($aslist = array(), $selectedId = "") {
        $ssOptionList = "";
		
        foreach ($aslist as $snCode => $ssName) {
            $ssName = strtolower($ssName);
            $ssName = ucwords($ssName);
            if ($selectedId != "" && $selectedId == $snCode) {
				
                $ssOptionList .= "<option value='$snCode' selected>$ssName</option>";
            } else {
                $ssOptionList .= "<option value='$snCode'>$ssName</option>";
            }
        }
        return $ssOptionList;
    }

    public function list_toOptionDairyYear($selectedId = "") {
        $endYear = 2005;
        $startYear = date('Y');
        $dailryYear = array();
        for($i=$startYear;$i >= $endYear;$i--){
            $dailryYear[$i]=$i;
        }

        $ssOptionList = "";
        foreach ($dailryYear as $snCode => $ssName) {
            if ($selectedId != "" && $selectedId == $snCode) {
                $ssOptionList .= "<option value='$snCode' selected>$ssName</option>";
            } else {
                $ssOptionList .= "<option value='$snCode'>$ssName</option>";
            }
        }
        return $ssOptionList;
    }
	public function Monthlylist_toOptionDairyYear($selectedId = "") {
        $endYear = 2013;
        $startYear = date('Y');
        $dailryYear = array();
        for($i=$startYear;$i >= $endYear;$i--){
            $dailryYear[$i]=$i;
        }

        $ssOptionList = "";
        foreach ($dailryYear as $snCode => $ssName) {
            if ($selectedId != "" && $selectedId == $snCode) {
                $ssOptionList .= "<option value='$snCode' selected>$ssName</option>";
            } else {
                $ssOptionList .= "<option value='$snCode'>$ssName</option>";
            }
        }
        return $ssOptionList;
    }
	
	function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
}