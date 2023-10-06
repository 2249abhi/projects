<!DOCTYPE html>
<html>
  <head>
    <title>Complaint</title>
    <style>
      .main-table-s th{ padding:5px; border:1px solid #ddd;text-align:left;}
      .main-table-s td{ padding:5px; border:1px solid #ddd;} 
    </style>
  </head>
  <body>
    <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td>
        <table cellpadding="0" cellspacing="0" width="650px" align="center" style="border:1px solid #ddd; background: #ffffff;">
    <tr>
      <td >
        <table cellpadding="0" cellspacing="0" width="100%" style="background: #f1f7f9" >
          <tr>
            <td style="padding:10px; text-align:center;">
              <a href="<?= $base_url ?>" >
                <img src="<?= $base_url ?>/assets/images/logo.png" alt="logo">
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <td style="border-top:1px solid #04b9f3;">
      <table cellpadding="0" cellspacing="0" class="main-table-s"  style="padding:10px;width:600px; margin:40px auto;" >
        <tbody>
          <tr>
            <th>Reference Number</th>
            <td><?=$referenceNumber;?></td>
          </tr>
          <tr>
            <th>Full Name</th>
            <td><?=$emailData['full_name'];?></td>
          </tr>
          <tr>
            <th>Email ID</th>
            <td><?=$emailData['email_id'];?></td>
          </tr>
          <tr>
            <th>Contact Number</th>
            <td><?=$emailData['mobile_number'];?></td>
          </tr>
          <tr>
            <th>Company name</th>
            <td><?=$emailData['company_name'];?></td>
          </tr>
          <tr>
            <th>Subject</th>
            <td><?=$emailData['subject'];?></td>
          </tr>
          <tr>
            <th>Address</th>
            <td><?=$emailData['address'];?></td>
          </tr>
          <tr>
            <th>Pincode</th>
            <td><?=$emailData['pincode'];?></td>
          </tr>
          <tr>
            <th>Comments</th>
            <td><?=$emailData['comments'];?></td>
          </tr>
        </tbody>
        </td>
        </tr>  
      </table>
      <div style="padding-bottom:10px;text-align:center;">
        <div class="footer-bottom-cont">
          <a href="https://www.facebook.com" style="padding-right:10px;">
            <img src="https://www.sidbi.in/assets/images/facebook-icon.png" alt="facebook">
          </a>
          <a href="https://twitter.com" style="padding-right:10px;">
            <img src="https://www.sidbi.in/assets/images/twitter-icon.png" alt="twitter">
          </a>
          <a href="https://plus.google.com">
            <img src="https://www.sidbi.in/assets/images/google-plus.png" alt="google-plus">
          </a>
          <p style="font-size:14px; line-height:14px; color:#4c4d4f;font-family:arial;text-align:center;">Copyright  ©2019 Small Industries Development Bank of India(SIDBI)</p>
        </div>
      </div>
  </body>
</html>