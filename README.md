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

form grup with fielset label
      create me inadd like this 
      - ['label' => 'Personal Information', 'inputs' => [['placeholder' => 'Enter Name', 'name' => 'personal_info__json__name[]', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''],
             
--repeatabl group jos ki json hote hai unke inputs only repeatble var mein hi honge 
--multiple file upload mein create mein jo keyname hoga usme [] add karenge aur yhi table_name and fieldname
some fields format 
  $this->form_image_field_name = [
            ['field_name'=>'image','single'=>true],
            ['field_name'=>'attribute_images','single'=>false,'image_model_name'=>'AttributeImage','parent_table_field'=>'attribute_id','table_name'=>'attribute_images']];
        
  $this->table_columns = [['column' => 'name', 'label' => 'Name', 'sortable' => 'Yes'],
      ['column' => 'status', 'label' => 'Status', 'sortable' => 'No'],
      ['column' => 'image', 'label' => 'Image', 'sortable' => 'No'],
      ['column' => 'attribute_images', 'label' => 'Images', 'sortable' => 'No'],
      ['column' => 'country', 'label' => 'Country', 'sortable' => 'No'], ['column' => 'personal_info', 'label' => 'PI', 'sortable' => 'No'], ['column' => 'city_name', 'label' => 'City Name', 'sortable' => 'No'], ['column' => 'created_at', 'label' => 'Date', 'sortable' => 'Yes']];

        $this->repeating_group_inputs = [['colname' => 'addon', 'label' => 'Addon', 'inputs' => [['placeholder' => 'Enter Name', 'name' => 'personal_info__json__name[]', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter qty', 'name' => 'personal_info__json__qty[]', 'label' => 'qty', 'tag' => 'input', 'type' => 'number', 'default' => '']]], ['colname' => 'cities', 'label' => 'City', 'inputs' => [['placeholder' => 'Enter Name', 'name' => 'city_name__json__name[]', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter qty', 'name' => 'city_name__json__qty[]', 'label' => 'qty', 'tag' => 'input', 'type' => 'number', 'default' => '']]]];
        
        $this->toggable_group = [['onval' => 'Active', 'inputs' => [['placeholder' => 'Enter Name', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter qty', 'name' => 'qty', 'label' => 'Qty', 'tag' => 'input', 'type' => 'text', 'default' => '']]]];



======================================================
- TaxClass:
  - module: 
    - TaxClass
  - plural:
    - tax_classes
  - tableName:
    - tax_classes
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
            
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
  - columns_with_select_field:
      
  - has_image:
    - FirstImage:
      - single:
        - field_name:
          - image
     -SecondImage:
                - multiple:
                      - table_name:
                          - attribute_images 
                      - image_model_name:
                          - AttributeImage 
                      - parent_table_field:
                          - attribute_id 
                      - field_name:
                          -image
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- CustomerSegment:
  - module: 
    - CustomerSegment
  - plural:
    - customer_segments
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
            
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
  - columns_with_select_field:
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- CommissionClass:
  - module: 
    - CommissionClass
  - plural:
    - commission_classes
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['rate'=>'required|numeric']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
            
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
  - columns_with_select_field:
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter rate','name'=>'rate','label'=>'Name','tag'=>'input','type'=>'number','default'=>'']"
   
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter rate','name'=>'rate','label'=>'Name','tag'=>'input','type'=>'number','default'=>$model->rate]"
   
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'rate','label'=>'Rate','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- DiscountClass:
  - module: 
    - DiscountClass
  - plural:
    - discount_classes
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['type'=>'required']"
    - "['minimum_purchase_qty'=>'nullable|numeric']"
    - "['minimum_purchase_amount'=>'nullable|numeric']"
    - "['minimum_weight'=>'nullable|numeric']"
    - "['minimum_pincode'=>'nullable|numeric']"
    - "['maximum_pincode'=>'nullable|numeric']"
    - "['use_limit_per_customer'=>'nullable|numeric']"
    - "['use_limit_by_order'=>'nullable|numeric']"
    - "['use_limit_per_customer'=>'nullable|numeric']"
    - "['applied_to_collections'=>'nullable|string']"
    - "['applied_to_products'=>'nullable|string']"
    - "['applied_to_customer_segments'=>'nullable|string']"
    - "['applied_to_customers'=>'nullable|string']"
    - "['start_date'=>'nullable']"
    - "['end_date'=>'nullable']"
    - "['rate'=>'required']"
    - "['charge_by'=>'required']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
            
  - filterable_fields:
    - "['name'=>'start_date','label'=>'Start Date','type'=>'date']"
  - columns_with_select_field:
    - "['label'=>'Category','field_name'=>'applied_to_collections','onChange'=>'showProducts(this.value)','multiple'=>true]"   
  
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter rate','name'=>'rate','label'=>'Name','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter minimum_purchase_qty','name'=>'minimum_purchase_qty','label'=>'minimum_purchase_qty','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter minimum_purchase_amount','name'=>'minimum_purchase_amount','label'=>'minimum_purchase_amount','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter minimum_weight','name'=>'minimum_weight','label'=>'minimum_weight','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter minimum_pincode','name'=>'minimum_pincode','label'=>'minimum_pincode','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter maximum_pincode','name'=>'maximum_pincode','label'=>'maximum_pincode','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter use_limit_per_customer','name'=>'use_limit_per_customer','label'=>'use_limit_per_customer','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter use_limit_by_order','name'=>'use_limit_by_order','label'=>'use_limit_by_order','tag'=>'input','type'=>'number','default'=>'']"
  - create2:
    - "['name'=>'applied_to_products','label'=>'Select Products','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'applied_to_customer_segments','label'=>'Select Customer Segment','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('CustomerSegment'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'applied_to_customers','label'=>'Select Customers','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
 
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter rate','name'=>'rate','label'=>'Name','tag'=>'input','type'=>'number','default'=>$model->rate]"
    - "['placeholder'=>'Enter minimum_purchase_qty','name'=>'minimum_purchase_qty','label'=>'minimum_purchase_qty','tag'=>'input','type'=>'number','default'=>$model->minimum_purchase_qty]"
    - "['placeholder'=>'Enter minimum_purchase_amount','name'=>'minimum_purchase_amount','label'=>'minimum_purchase_amount','tag'=>'input','type'=>'number','default'=>$model->minimum_purchase_amount]"
    - "['placeholder'=>'Enter minimum_weight','name'=>'minimum_weight','label'=>'minimum_weight','tag'=>'input','type'=>'number','default'=>$model->minimum_weight]"
    - "['placeholder'=>'Enter minimum_pincode','name'=>'minimum_pincode','label'=>'minimum_pincode','tag'=>'input','type'=>'number','default'=>$model->minimum_pincode]"
    - "['placeholder'=>'Enter maximum_pincode','name'=>'maximum_pincode','label'=>'maximum_pincode','tag'=>'input','type'=>'number','default'=>$model->maximum_pincode]"
    - "['placeholder'=>'Enter use_limit_per_customer','name'=>'use_limit_per_customer','label'=>'use_limit_per_customer','tag'=>'input','type'=>'number','default'=>$model->use_limit_per_customer]"
    - "['placeholder'=>'Enter use_limit_by_order','name'=>'use_limit_by_order','label'=>'use_limit_by_order','tag'=>'input','type'=>'number','default'=>$model->use_limit_by_order]"
  - edit2:
    - "['name'=>'applied_to_products','label'=>'Select Products','tag'=>'select','type'=>'select','default'=>json_decode($model->applied_to_products,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'applied_to_customer_segments','label'=>'Select Customer Segment','tag'=>'select','type'=>'select','default'=>$model->applied_to_customer_segments?json_decode($model->applied_to_customer_segments,true):[],'custom_key_for_option'=>'name','options'=>$this->getList('CustomerSegment'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'applied_to_customers','label'=>'Select Customers','tag'=>'select','type'=>'select','default'=>$model->applied_to_customers?json_decode($model->applied_to_customer,true):[],'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    
  - radio_checkbox_group:
    - "['name'=>'charge_by','label'=>'Charge By ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Percent','value'=>'Percent'],(object)['label'=>'Flat','value'=>'Flat']],'inline'=>true,'default'=>'','attr'=>[]]"
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>true,'default'=>'','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'rate','label'=>'Rate','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- ShippingClass:
  - module: 
    - ShippingClass
  - plural:
    - shipping_classes
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['type'=>'required']"
    - "['minimum_weight'=>'nullable|numeric']"
    - "['maximum_weight'=>'nullable|numeric']"
    - "['charge'=>'required|numeric']"
    - "['unit'=>'required|string']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
    - "['name'=>'type','label'=>'Type']"
            
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
  - columns_with_select_field:
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter Unit','name'=>'unit','label'=>'Unit','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter charge','name'=>'charge','label'=>'Charge','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter minimum_weight','name'=>'minimum_weight','label'=>'Minimum Weight','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter maximum_weight','name'=>'maximum_weight','label'=>'Maximum Weight','tag'=>'input','type'=>'number','default'=>'']"
  - create2:
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter Unit','name'=>'unit','label'=>'Unit','tag'=>'input','type'=>'text','default'=>$model->unit]"
    - "['placeholder'=>'Enter charge','name'=>'charge','label'=>'Charge','tag'=>'input','type'=>'number','default'=>$model->charge]"
    - "['placeholder'=>'Enter minimum_weight','name'=>'minimum_weight','label'=>'Minimum Weight','tag'=>'input','type'=>'number','default'=>$model->minimum_weight]"
    - "['placeholder'=>'Enter maximum_weight','name'=>'maximum_weight','label'=>'Maximum Weight','tag'=>'input','type'=>'number','default'=>$model->maximum_weight]"
  
  - edit2:
       
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>true,'default'=>'','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'unit','label'=>'Unit','sortable'=>'Yes']"
    - "['column'=>'charge','label'=>'Charge','sortable'=>'Yes']"
    - "['column'=>'minimum_weight','label'=>'Minimum Weight','sortable'=>'Yes']"
    - "['column'=>'maximum_weight','label'=>'Maximum Weight','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- Country:
  - module: 
    - Country
  - plural:
    - countries
  - modal:
    - true
  - export:
    - false
  - export_fields:
    
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['code'=>'nullable|string|max:10']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
    - "['name'=>'code','label'=>'Code']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
  - columns_with_select_field:
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter Code','name'=>'code','label'=>'Code','tag'=>'input','type'=>'text','default'=>'']"
  
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter Code','name'=>'code','label'=>'Code','tag'=>'input','type'=>'text','default'=>$model->code]"
  
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'code','label'=>'Code','sortable'=>'No']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- State:
  - module: 
    - State
  - plural:
    - states
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'required|numeric']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>false]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- City:
  - module: 
    - City
  - plural:
    - cities
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'required|numeric']"
    - "['state'=>'required|numeric']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
    - "['name'=>'state','label'=>'State','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>false]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
   
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>$model->state,'custom_key_for_option'=>'name','options'=>$this->getList('State',['id'=>$model->state]),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
       
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'state','label'=>'State','sortable'=>'No']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- Pincode:
  - module: 
    - Pincode
  - plural:
    - pincodes
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'required|numeric']"
    - "['state'=>'required|numeric']"
    - "['city'=>'required|numeric']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
    - "['name'=>'state','label'=>'State','type'=>'select']"
    - "['name'=>'city','label'=>'City','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>false]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'city','label'=>'City','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
   
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>$model->state,'custom_key_for_option'=>'name','options'=>$this->getList('State',['id'=>$model->state]),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'city','label'=>'City','tag'=>'select','type'=>'select','default'=>$model->city,'custom_key_for_option'=>'name','options'=>$this->getList('City',['id'=>$model->city]),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
       
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'state','label'=>'State','sortable'=>'No']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- Tax:
  - module: 
    - Tax
  - plural:
    - taxes
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'required|numeric']"
    - "['state'=>'required|numeric']"
    - "['city'=>'required|numeric']"
    - "['rate'=>'required|numeric']"
    - "['class_id'=>'required|numeric']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
    - "['name'=>'state','label'=>'State','type'=>'select']"
    - "['name'=>'city','label'=>'City','type'=>'select']"
    - "['name'=>'class_id','label'=>'Tax Class','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'TaxClass','field_name'=>'class_id','onChange'=>'','multiple'=>false]"         
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>true]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter Rate','name'=>'rate','label'=>'Rate','tag'=>'input','type'=>'number','default'=>'']"
   
  - create2:    
    - "['name'=>'state','label'=>'Select States','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'city','label'=>'Select Cities','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'pincode','label'=>'Select Pincodes','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
   
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter Rate','name'=>'rate','label'=>'Rate','tag'=>'input','type'=>'number','default'=>$model->rate]"
   
  - edit2:    
    - "['name'=>'state','label'=>'Select States','tag'=>'select','type'=>'select','default'=>json_decode($model->state,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'city','label'=>'Select Cities','tag'=>'select','type'=>'select','default'=>json_decode($model->city,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'pincode','label'=>'Select Pincodes','tag'=>'select','type'=>'select','default'=>json_decode($model->pincode,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
   
  - radio_checkbox_group:
    
  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'state','label'=>'State','sortable'=>'No']"
    - "['column'=>'city','label'=>'City','sortable'=>'No']"
    - "['column'=>'pincode','label'=>'Pincode','sortable'=>'No']"
    - "['column'=>'rate','label'=>'Rate','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- ShippingZone:
  - module: 
    - ShippingZone
  - plural:
    - Shipping_zones
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'nullable']"
    - "['state'=>'nullable']"
    - "['city'=>'nullable']"
    - "['pincode'=>'nullable']"
    - "['min_pincode'=>'nullable|numeric']"
    - "['max_pincode'=>'nullable|numeric']"
    - "['duration'=>'required']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
    - "['name'=>'state','label'=>'State','type'=>'select']"
    - "['name'=>'city','label'=>'City','type'=>'select']"
    - "['name'=>'pincode','label'=>'Pincode','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>true]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter duration','name'=>'duration','label'=>'Duration','tag'=>'input','type'=>'text','default'=>'']"
    - "['placeholder'=>'Enter minimum pincode','name'=>'minimum_pincode','label'=>'Minimum Pincode','tag'=>'input','type'=>'number','default'=>'']"
    - "['placeholder'=>'Enter maximum pincode','name'=>'maximum_pincode','label'=>'Maximum Pincode','tag'=>'input','type'=>'number','default'=>'']"
   
  - create2:    
    - "['name'=>'state','label'=>'Select States','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'city','label'=>'Select Cities','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'pincode','label'=>'Select Pincodes','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
   
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    - "['placeholder'=>'Enter duration','name'=>'duration','label'=>'Duration','tag'=>'input','type'=>'text','default'=>$model->duration]"
    - "['placeholder'=>'Enter minimum pincode','name'=>'minimum_pincode','label'=>'Minimum Pincode','tag'=>'input','type'=>'number','default'=>$model->minimum_pincode]"
    - "['placeholder'=>'Enter maximum pincode','name'=>'maximum_pincode','label'=>'Maximum Pincode','tag'=>'input','type'=>'number','default'=>$model->maximum_pincode]"
      
  - edit2:    
    - "['name'=>'state','label'=>'Select States','tag'=>'select','type'=>'select','default'=>json_decode($model->state,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'city','label'=>'Select Cities','tag'=>'select','type'=>'select','default'=>json_decode($model->city,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'pincode','label'=>'Select Pincodes','tag'=>'select','type'=>'select','default'=>json_decode($model->pincode,true),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
   
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'state','label'=>'State','sortable'=>'No']"
    - "['column'=>'city','label'=>'City','sortable'=>'No']"
    - "['column'=>'pincode','label'=>'Pincode','sortable'=>'No']"
    - "['column'=>'duration','label'=>'Duration','sortable'=>'No']"
    - "['column'=>'minimum_pincode','label'=>'Minimum Pincode','sortable'=>'No']"
    - "['column'=>'maximum_pincode','label'=>'Maximum Pincode','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- Store:
  - module: 
    - Store
  - plural:
    - stores
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['country'=>'required|numeric']"
    - "['state'=>'required|numeric']"
    - "['city'=>'required|numeric']"
    - "['address'=>'required']"
    - "['owner'=>'nullable']"
    - "['status'=>'required']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'country','label'=>'Country','type'=>'select']"
    - "['name'=>'state','label'=>'State','type'=>'select']"
    - "['name'=>'city','label'=>'City','type'=>'select']"
    - "['name'=>'owner','label'=>'Owner','type'=>'select']"
  - columns_with_select_field:
    - "['label'=>'Country','field_name'=>'country','onChange'=>'showStates(this.value)','multiple'=>false]"         
    - "['label'=>'User','field_name'=>'owner','onChange'=>'','multiple'=>false]"         
      
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'city','label'=>'City','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['placeholder'=>'Enter Address','name'=>'address','label'=>'Address','tag'=>'input','type'=>'textarea','default'=>'']"
   
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
   
  - edit2:
    - "['name'=>'state','label'=>'State','tag'=>'select','type'=>'select','default'=>$model->state,'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'city','label'=>'City','tag'=>'select','type'=>'select','default'=>$model->city,'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['placeholder'=>'Enter Address','name'=>'address','label'=>'Address','tag'=>'input','type'=>'textarea','default'=>$model->address]"
     
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'state','label'=>'State','sortable'=>'No']"
    - "['column'=>'city','label'=>'City','sortable'=>'No']"
    - "['column'=>'owner','label'=>'Owner','sortable'=>'No']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- ProductAssignVendor:
  - module: 
    - ProductAssignVendor
  - tableName:
    - product_assigned_vendor
  - plural:
    - product_assign_vendors
  - modal:
    - true
  - export:
    - true
  - export_fields:
    - "['vendor_id','product_id','created_at']"
  
  - validation:
    - "['vendor_id'=>'required|numeric']"
    - "['product_id'=>'required|string']"
    
        
  - searchable_fields:
    - "['name'=>'vendor_id','label'=>'Vendor']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'vendor_id','label'=>'Vendor','type'=>'select']"
    
  - columns_with_select_field:
  - has_image:
  - create:
    - "['name'=>'vendor_id','label'=>'Select Vendor','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('User',['role'=>'Vendor']),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'category_id','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('Category'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
     
  - create2:    
  - edit:
    - "['name'=>'vendor_id','label'=>'Select Vendor','tag'=>'select','type'=>'select','default'=>$model->vendor_id,'custom_key_for_option'=>'name','options'=>$this->getList('User',['role'=>'Vendor']),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'category_id','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>$this->getFieldValuesFromModelAsArray('ProductAssignVendor','category_id',['vendor_id'=>$model->vendor_id]),'custom_key_for_option'=>'name','options'=>$this->getList('Category'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>$this->getFieldValuesFromModelAsArray('ProductAssignVendor','product_id',['vendor_id'=>$model->vendor_id]),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
     
  - edit2:
      
  - radio_checkbox_group:
  - index_page:
    - "['column'=>'vendor_id','label'=>'Vendor','sortable'=>'Yes']"
    - "['column'=>'category_id','label'=>'Category','sortable'=>'No']"
    - "['column'=>'product_id','label'=>'Product','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- ProductAssignStore:
  - module: 
    - ProductAssignStore
  - tableName:
    - product_assigned_store
  - plural:
    - product_assign_stores
  - modal:
    - true
  - export:
    - true
  - export_fields:
    - "['vendor_id','product_id','created_at']"
  
  - validation:
    - "['store_id'=>'required|numeric']"
    - "['product_id'=>'required|string']"
    
        
  - searchable_fields:
    - "['name'=>'product_id','label'=>'Product']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    - "['name'=>'store_id','label'=>'Vendor','type'=>'select']"
    
  - columns_with_select_field:
  - has_image:
  - create:
    - "['name'=>'store_id','label'=>'Select Store','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('Store'),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'category_id','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('Category'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
     
  - create2:    
  - edit:
    - "['name'=>'store_id','label'=>'Select Store','tag'=>'select','type'=>'select','default'=>$model->store_id,'custom_key_for_option'=>'name','options'=>$this->getList('Store'),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'category_id','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('Category'),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>$this->getFieldValuesFromModelAsArray('ProductAssignVendor','product_id',['store_id'=>$model->store_id]),'custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
     
  - edit2:
      
  - radio_checkbox_group:
  - index_page:
    - "['column'=>'store_id','label'=>'Store','sortable'=>'Yes']"
    - "['column'=>'product_id','label'=>'Product','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- VendorAssignCommission:
  - module: 
    - VendorAssignCommission
  - tableName:
    - vendor_assigned_commission
  - plural:
    - vendor_assign_commission
  - modal:
    - true
  - export:
    - true
  - export_fields:
    - "['vendor_id','product_id','created_at']"
  
  - validation:
    - "['vendor_ids'=>'required']"
    - "['commission_id'=>'required|numeric']"
    
        
  - searchable_fields:
    - "['name'=>'vendor_id','label'=>'Vendor']"
    - "['name'=>'commission_id','label'=>'Commission Class']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    
  - columns_with_select_field:
    - "['label'=>'CommissionClass','field_name'=>'commission_id','onChange'=>'','multiple'=>false]"   
  
  - has_image:
  - create:
    - "['name'=>'vendor_id','label'=>'Select Vendor','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('User',['role'=>'Vendor']),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
      
  - create2:    
  - edit:
    - "['name'=>'vendor_id','label'=>'Select Vendor','tag'=>'select','type'=>'select','default'=>$this->getFieldValuesFromModelAsArray('VendorAssignCommission','vendor_id',['commission_id'=>$model->commission_id]),'custom_key_for_option'=>'name','options'=>$this->getList('User',['role'=>'Vendor']),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
     
  - edit2:
  - radio_checkbox_group:
  - index_page:
    - "['column'=>'vendor_id','label'=>'Vendor','sortable'=>'Yes']"
    - "['column'=>'commission_id','label'=>'Commission Class','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- ProductAddon:
  - module: 
    - ProductAddon
  - plural:
    - product_addons
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['product_id'=>'required|max:200|string']"
    - "['addons'=>'required|string']"
        
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
   
  - columns_with_select_field:
    - "['label'=>'Category','field_name'=>'category','onChange'=>'showProductsForAddon(this.value)','multiple'=>false]"         
      
  - has_image:
  - create:
    - "['name'=>'category','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>'1','custom_key_for_option'=>'name','options'=>$this->getList('Category',['status'=>'Active']),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
  
   
  - create2:    
    - "['name'=>'addons','label'=>'Select Addons','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
   
  - edit:
    - "['name'=>'category','label'=>'Select Category','tag'=>'select','type'=>'select','default'=>'1','custom_key_for_option'=>'name','options'=>$this->getList('Category',['status'=>'Active']),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
    - "['name'=>'product_id','label'=>'Select Product','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>[],'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]]" 
     
  - edit2:
    - "['name'=>'addons','label'=>'Select Addons','tag'=>'select','type'=>'select','default'=>json_decode($model->addons,true),'custom_key_for_option'=>'name','options'=>$this->getListFromIndexArray(json_decode($model->addons,true)),'custom_id_for_option'=>'id','multiple'=>true,'attr'=>[]]" 
       
  - radio_checkbox_group:
   
  - index_page:
    - "['column'=>'product_id','label'=>'Product Name','sortable'=>'No']"
    - "['column'=>'addons','label'=>'Add ons','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'No']"
- Manufacturer:
  - module: 
    - Manufacturer
  - plural:
    - manufactureres
  - modal:
    - true
  - export:
    - false
  - export_fields:
   
  - validation:
    - "['name'=>'required|max:200|string']"
         
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    
  - columns_with_select_field:
  - has_image:
  - create:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>'']"
   
  - create2:    
  - edit:
    - "['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name]"
    
  - edit2:    
  - radio_checkbox_group:
    - "['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]]"

  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'Yes']"

     