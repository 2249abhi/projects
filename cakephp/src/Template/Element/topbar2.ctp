<!-- Topbar Start -->
<header id="header">
  <div class="topStrip">
    <div class="container abhayen">
      <div class="row">
        <div class="col-4">
          <div class="common-left clearfix">
            <ul>
              <li class="gov-india"><span class="li_eng responsive_go_eng"><a target="_blank" href="https://india.gov.in/" title="GOVERNMENT OF INDIA,External Link that opens in a new window" role="link">GOVERNMENT OF INDIA</a></span></li>
              <li class="ministry"><span class="li_eng responsive_minis_eng"><a target="_blank" href="https://doe.gov.in/" title="Ministry of Finance ,External Link that opens in a new window" role="link">Ministry of Finance </a></span></li>
            </ul>
          </div>
        </div>
        <div class="col-8 text-right">
          <ul class="topNav">
            <li><a href="#skipCont">Skip To Main Content</a></li>
            <li><a href="/en/home/screen-reader" target="_blank">&nbsp;Screen Reader Access</a></li>
            <li>
              <div class="textResizeWrapper cf" id="accessControl">
                <input type="button" name="font_normal" value="A-" onClick="set_font_size('decrease');" id="font_normal" title="Decrease Font Size" class="fontScaler normal font-normal current">
                <input type="button" name="font_large" value="A" onClick="set_font_size('normal');" id="font_large" title="Normal Font Size" class="fontScaler large font-large">
                <input type="button" name="font_larger" value="A+" onClick="set_font_size('increase');" id="font_larger" title="Increase Font Size" class="fontScaler largest font-largest">
              </div>
            </li>
            <li>
              <div class="colorscheme">
                <input type="submit" name="normal" value="" id="normal" title="Standard View" class="hight_contrast_remove contrastChanger normal current" onClick="return setColorDark('');">
                <input type="submit" name="wob" value="" id="wob" title="High Contrast View" class="hight_contrast contrastChanger wob" onClick="return setColorDark('wob');">
              </div>
            </li>
            <li>
              <select onChange="_ChangeLang(this.value);">
                <option value="en" selected="selected">English</option>
                <option value="hi">Hindi/हिन्दी</option>
              </select>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row pt-2 pb-2">
      <div class="col-sm-3">
        <div class="logo"> <a href="<?=$this->Url->build(['controller'=>'home','action'=>'index'])?>" title="Go to home" class="site_logo" rel="home"> <img id="logo" class="emblem" src="https://s3f1981e4bd8a0d6d8462016d2fc6276b3.s3waas.gov.in/wp-content/themes/sdo-theme/images/emblem.png" alt="State Emblem of India">
          <div class="logo_text"> <strong lang="mr">राष्ट्रीय सहकारी डेटाबेस</strong>
            <h1 class="h1-logo">National Cooperative Database</h1>
          </div>
          </a> </div>
      </div>
      <div class="col-sm-5"> </div>
      <div class="col-sm-4">
        <div class="logosblock">
          <div class="logo-in sb-n"> <a href="https://swachhbharatmission.gov.in/" target="_blank" ><img src="<?=$this->request->webroot?>frontend/img/swach-bharat.png" alt=""></a> </div>
          <div class="logo-in lastb"> <a href="#" target="_blank" ><img src="<?=$this->request->webroot?>frontend/img/MSCS_LOGO.png"  alt=""></a> </div>
          <div class="logo-in g20"> <a href="https://www.g20.org/en/" target="_blank" ><img src="<?=$this->request->webroot?>frontend/img/G20.png" alt=""></a> </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- Topbar End -->
