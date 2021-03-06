<div class="container" xmlns="http://www.w3.org/1999/html">
<p align="center"><a href="http://www.adsonance.com"><img src="<?php assetLink(array('logo-adsonance-black.png'=>'image')); ?>"/></a></p>
    <div class="row">
        <div class="span8 offset2 well">

            <?php
            $attributes = array('class' => 'form-horizontal');
            echo form_open_multipart('advertiser/index/signup', $attributes); ?>
            <fieldset>
                <legend>Create an Account</legend>

                <?php if(isset($errors)):?>
                <div class="alert alert-error">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <?php echo $errors; ?>
                </div>
                    <?php endif;?>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">E-Mail</label>

                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputEmail'])){ echo $data['inputEmail'];}?>" name="inputEmail" type="email" id="inputEmail" placeholder="E-Mail (Required)">
                        <a href="#" id="emailttp" data-html='true' data-placement="right" rel="tooltip" title="E-mail address that is currenctly in use. </br> E-mail verification will be required."><i class="icon-question-sign"></i> </a>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPassword">Password</label>
                    <div class="controls">
                        <input class="input-xlarge" name="inputPassword" type="password" id="inputPassword" placeholder="Password (Required)">
                        <a href="#" id="passwordttp" data-placement="right" rel="tooltip" title="Password must be atleast 8 characters.</br>Use alphanumeric characters.</br>Password is case sensitive." data-html="true"><i class="icon-question-sign"></i> </a>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputReEnterPassword">Re-Enter Password</label>
                    <div class="controls">
                        <input class="input-xlarge" name="inputReEnterPassword" type="password" id="inputReEnterPassword" placeholder="Re-Enter Password (Required)">
                        <p class="text-error" id="inputReEnterPasswordMessage"></p>
                    </div>
                </div>


                <div class="control-group">
                    <label class="control-label" for="inputCurrency">Currency</label>
                    <div class="controls">
                        <select name="inputCurrency" class="input-medium" id="inputCurrency"  lastSelected="<?php if(isset($data['inputCurrency'])){ echo $data['inputCurrency'];}?>" >
                            <option value="INR">RUPEES (INR)</option>                           
                            <option value="USD">DOLLAR (USD)</option>

                        </select>
                        <a href="#" id="currencyttp" data-placement="right" rel="tooltip" title="This currency will be used for all your transactions."><i class="icon-question-sign"></i> </a>
                    </div>
                </div>


                <div class="control-group">
                    <label class="control-label" for="inputFirstname">First Name</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputFirstname'])){ echo $data['inputFirstname'];}?>"  name="inputFirstname" type="text" id="inputFirstname" placeholder="First Name (Required)">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputLastname">Last Name</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputLastname'])){ echo $data['inputLastname'];}?>"  name="inputLastname" type="text" id="inputLastname" placeholder="Last Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputCompany">Company</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputCompany'])){ echo $data['inputCompany'];}?>"  name="inputCompany" type="text" id="inputCompany" placeholder="Company">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputDesignation">Designation</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputDesignation'])){ echo $data['inputDesignation'];}?>"  name="inputDesignation" type="text" id="inputDesignation" placeholder="Designation">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPhone">Phone</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputPhone'])){ echo $data['inputPhone'];}?>"  name="inputPhone" type="text" id="inputPhone" placeholder="Phone (Required)">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputWebsite">Website</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputWebsite'])){ echo $data['inputWebsite'];}?>"  name="inputWebsite" type="text" id="inputWebsite" placeholder="Website">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputStreetApp">Street/App.</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputStreetApp'])){ echo $data['inputStreetApp'];}?>"  name="inputStreetApp" type="text" id="inputStreetApp" placeholder="Street/Appartment Address">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputCity">City</label>
                    <div class="controls">
                        <input class="input-xlarge" value="<?php if(isset($data['inputCity'])){ echo $data['inputCity'];}?>"  name="inputCity" type="text" id="inputCity" placeholder="City">
                    </div>
                </div>


                <div class="control-group">
                    <label class="control-label" for="inputCountry">Country</label>
                    <div class="controls">
                        <select name="inputCountry" class="input-medium" id="inputCountry"  lastSelected="<?php if(isset($data['inputCountry'])){ echo $data['inputCountry'];}?>" >
                        <option value="Afganistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Aruba">Aruba</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Belize">Belize</option>
                        <option value="Benin">Benin</option>
                        <option value="Bermuda">Bermuda</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bonaire">Bonaire</option>
                        <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Brazil">Brazil</option>
                        <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                        <option value="Brunei">Brunei</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Canary Islands">Canary Islands</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Channel Islands">Channel Islands</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cocos Island">Cocos Island</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Cote DIvoire">Cote D'Ivoire</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Curaco">Curacao</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="East Timor">East Timor</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Falkland Islands">Falkland Islands</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="French Southern Ter">French Southern Ter</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Great Britain">Great Britain</option>
                        <option value="Greece">Greece</option>
                        <option value="Greenland">Greenland</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Hawaii">Hawaii</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hong Kong">Hong Kong</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India" selected="selected">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Isle of Man">Isle of Man</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Korea North">Korea North</option>
                        <option value="Korea Sout">Korea South</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Laos">Laos</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Macau">Macau</option>
                        <option value="Macedonia">Macedonia</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Midway Islands">Midway Islands</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Nambia">Nambia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherland Antilles">Netherland Antilles</option>
                        <option value="Netherlands">Netherlands (Holland, Europe)</option>
                        <option value="Nevis">Nevis</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Niue">Niue</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau Island">Palau Island</option>
                        <option value="Palestine">Palestine</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Phillipines">Philippines</option>
                        <option value="Pitcairn Island">Pitcairn Island</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Republic of Montenegro">Republic of Montenegro</option>
                        <option value="Republic of Serbia">Republic of Serbia</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="St Barthelemy">St Barthelemy</option>
                        <option value="St Eustatius">St Eustatius</option>
                        <option value="St Helena">St Helena</option>
                        <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                        <option value="St Lucia">St Lucia</option>
                        <option value="St Maarten">St Maarten</option>
                        <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                        <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
                        <option value="Saipan">Saipan</option>
                        <option value="Samoa">Samoa</option>
                        <option value="Samoa American">Samoa American</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syria</option>
                        <option value="Tahiti">Tahiti</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Erimates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States of America">United States of America</option>
                        <option value="Uraguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City State">Vatican City State</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                        <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                        <option value="Wake Island">Wake Island</option>
                        <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Zaire">Zaire</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" value="<?php if(isset($data['inputPostalZip'])){ echo $data['inputPostalZip'];}?>"   for="inputPostalZip">Postal / ZIP Code</label>
                    <div class="controls">
                        <input class="input-xlarge" name="inputPostalZip" type="text" id="inputPostalZip" placeholder="Postal / ZIP Code">
                    </div>
                </div>



                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">SignUp</button>
                    <a type="button" class="btn" href="javascript:goBack()">Cancel</a>
                </div>
            </fieldset>
            </form>
        </div>
    </div>
</div>


<?php
loadAsset(array('jquery-1.7.1.min.js'=>'script'));
loadBootstrap('script.min') ;
?>

<script type="text/javascript">
    $('#emailttp').tooltip();
    $('#passwordttp').tooltip();
    $('#currencyttp').tooltip();

    var country =$('#inputCountry').attr('lastSelected');
    var currency =$('#inputCurrency').attr('lastSelected');

    var countryItems=$('#inputCountry').children('option[value="'+country+'"]');
    var currencyItems=$('#inputCurrency').children('option[value="'+currency+'"]');
    countryItems.attr('selected','selected');
    currencyItems.attr('selected','selected');

    /*
    $(document).ready(function(){
        $('#inputPassword').on('keyup blur focusin select',inputPasswordHandler);
        $('#inputVerifyPassword').on('keyup blur focusin select',inputVerifyPasswordHandler);
        $('#inputEmail').on('keyup blur focusin select',validateEmail);

    });

    function inputPasswordHandler(event){
        var value=$(this).attr('value');
        var valueSize=value.length;
        var hasNumber=value.match('[0-9]');
        var hasLowerCaseAlphabet=value.match('[a-z]');
        var hasUpperCaseAlphabet=value.match('[A-Z]');

        if(hasNumber && hasLowerCaseAlphabet && hasUpperCaseAlphabet && valueSize>8){
            $('#inputPasswordMessage1').text('Very Strong');
        }else if(hasNumber && hasLowerCaseAlphabet && hasUpperCaseAlphabet && valueSize<6){
            $('#inputPasswordMessage1').text('Weak Password');
        }else if(hasNumber && hasLowerCaseAlphabet && hasUpperCaseAlphabet && valueSize>=6 && valueSize<=8){
            $('#inputPasswordMessage1').text('Moderate Password');
        }else if(!hasNumber || !hasLowerCaseAlphabet || !hasUpperCaseAlphabet){
            $('#inputPasswordMessage1').text('Must have Lower Case,Upper Case and a Number');
            $(this).parent().parent().removeClass();
            $(this).parent().parent().addClass('control-group error');
        }
    }



    function inputVerifyPasswordHandler(event){
        var password=$('#inputPassword').attr('value');
        var value=$(this).attr('value');

        if(password==value){
            $('#inputVerifyPasswordMessage').text('OK');
        }else{
            $('#inputVerifyPasswordMessage').text(value.split('a').length-1);
        }
    }


    function validateEmail(){
        var email=$(this).attr('value');
        var splitAt=email.split('a').length;

        if(email.indexOf('@')>-1 && email.indexOf('.')>-1 && email.length>(email.lastIndexOf('.')+1) && email.lastIndexOf('@')<email.lastIndexOf('.')){
            $(this).parent().parent().removeClass();
            $(this).parent().parent().addClass('control-group success');
        }else{
            $(this).parent().parent().removeClass();
            $(this).parent().parent().addClass('control-group error');        }
    }*/

</script>