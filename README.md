this is common file requied in all themes  and corresponding css also

 <script src="{{asset('commonjs/jquery.validate.min.js')}}"></script>
    <script src="{{asset('commonjs/jquery.filer.min.js')}}"></script> --fole file upload r multip
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
      
    <script src="{{asset('assets/js/bootstrap-filestyle.min.js')}}"></script>--styling file browser
    
    
    
    
    1-file multiple input mein [] add hoga name mein naki select mein
2-every select will have attr=['data-tag'=>true] if the select is multi select and also as input like tag input othwerwise not add this 
3-Agar form mein aisa field hai jo ki json form mein store karna hai jo ki other table ke data hai jaise contry list store karna kisi table column mein 
 then no need to store country id as array ,store country name as array otherwise join issue ,and prevent deletion of the id using foreing contraint ,set safe delete option thre in country table stored as json.Agar ek json form field is dependent on value of other json form field 
like country select and state select with mutiple values then in each select option value shoudl be in form 
<option value="$id-$name">$name</opyion> ,js file automatically fetches dependent state data ,no need to do much 
qki js automaticall value submit hone se pahle is format ko check karta hai aur id extract karke hi server pe bhejta hai
==agar inline =true not work in form builder then netjose ke form-builder file mein jaake line no 353 mein aise kardo [$inline, 'form-check-inline'], 

DELETE  FROM `permissions` WHERE `id`>12

plural lowercase should havve underscore for words
1-file multiple input mein [] add hoga name mein naki select mein
2-every select will have attr=['data-tag'=>true] if the select is multi select and also as input like tag input othwerwise not add this 
3-Agar form mein aisa field hai jo ki json form mein store karna hai jo ki other table ke data hai jaise contry list store karna kisi table column mein 
 then no need to store country id as array ,store country name as array otherwise join issue ,and prevent deletion of the id using foreing contraint ,set safe delete option thre in country table stored as json.if non dependent on each other then that select will have format  
 <option value="$id">$name</opyion>
 4-Agar ek json form field is dependent on value of other json form field 
  like country select and state select with mutiple values then in each select option value shoudl be in form 
<option value="$id-$name">$name</opyion> ,js file automatically fetches dependent state data ,no need to do much 

5-agar multiple select field dpend on each ther but all are mutple value then dont use modal but use simple page and write those select boxes manually 

in view page not automatic ,but only those select boxes code manually that are dednent 
6-image fiel default value set karne pe image automaically dikhega on edit qki wo hi src hota hai
 7-every input shoud have placeholder,tag attr,default
 8-input type file mein multiple image default value format 
 default=['id'=>'1','name'=>'image2.jpeg','folder'=>'users']
 for single input file 
 default="imagename"
10 --file other than image should hae name like _file at the end like music_file,pdf_file,address_file like this to idnetify in view
11-==if multiple images then ek to relatiosnship define karo hasMany ka name same as table name with __images or __files appended yhi name index column declaration mein hoga 
11 incase of multiple file upload keep field_name and table name smae with _images or _files or _docs or _pdfs at the end;

12- edit mein json repeatable fields mein koij value nai dena hota automatically populate ho jate hai on edit
13- togle di ke liye onChange attr mein toggleDivDisplay(val,plural_lowercase,container_id) daalna hoga whee conaierid is same ha has_toggle_div array mein toggle_div value
   ==vaue dete time use \" symbol or error   liek this ['onChange'=>'toggleDivDisplay(this.value,\"attribute_families\",\"status_toggle\",$model->id)'
   modle->id is used to populate when on change occurs in edit so al is not lost there on change 
  14- inputidforvalue' => 'inp-herenameofinputlikeradioorselect-'.$model->status 

15-index mein agar multiple image fetch karn ahai to name key mein related table ka name dena hai like attrobite_images  
16-====input type checbox multiple true mein default mein hamesha at least blank arrya dalana hota hai or error
17- readme mein jo fields inputs hone unme [] appened hoga 
16-toggle_div_id  ki value will be 'namekeyofinput_toggle'
DELETE  FROM `permissions` WHERE `id`>12 

