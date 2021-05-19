/*
 * 
 * Root App for Form Builder.
 * 
 */
const app = new Vue({
    el: '#erf_fb_app',
    data: {
        fields: [],
        dragDisabled: false,
        title: erf_data.form_data.title,
        fieldTypes: erf_data.field_types,
        showDisplayFields: false,
        showInputFields: false,
        /**
         * 
         * Contains attributes for each type of field (Except composite fields. For example: Address)
         * Any new attribute has to be added here for any type of the field. 
         * Attribute default values can be given here.
         * Included fields are:
         * 1. Textbox
         * 2. Password
         * 3. User Email
         * 4. Submit and Reset button
         * 5. Textarea for multiline input
         * 6. HTML fields (Splitter,Header,RichText,Separator)
         * 7. Telephone
         * 8. URL
         * 9. Email
         * 10. Number
         * 11. Hidden
         * 12. Date
         * 13. File
         * 14. Username
         * 15. Dropdown (Single and Multiselect)
         * 16. Radio Group 
         * 17. Checkbox Group
         */
        schema: {
            text: {
                label: 'Text Field',
                type: 'text',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                masking: '',
                pattern: '',
                addUserField: false,
                addUserFieldMap: '',
                enableUnique: false,
                required: false,
                icon:'',
                width:12,
            },
            password: {
                label: 'Password',
                type: 'password',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                masking: '',
                pattern: '',
                confirmPasswordLabel: 'Confirm Password',
                confirmPassword: true,
                required: true,
                icon:'',
                width:12,
            },
            user_email: {
                label: 'User Email',
                type: 'user_email',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                masking: '',
                pattern: '',
                required: true,
                icon:'',
                width:12,
            },
            button: {
                type: 'button',
                subtype: 'button',
                label: 'Button',
                className: 'btn btn-default',
                name: '',
                style: 'default',
                dataErfBtnPos: 'left',
                icon:'',
                width:12,
            },
            textarea: {
                label: 'Textarea',
                type: 'textarea',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                rows: 0,
                cols: 0,
                required: false,
                addUserField: false,
                addUserFieldMap: '',
                icon:'',
                width:12
            },
            splitter: {
                label: 'Splitter',
                type: 'splitter',
                className: '',
                dataRefLabel: '',
                width:12,
            },
            richtext: {
                label: 'Place your content here.',
                type: 'richtext',
                class: 'fb-rich-text',
                dataRefLabel: '',
                dataRefId: '',
                width:12,
            },
            tel: {
                label: 'Phone',
                type: 'tel',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                masking: '',
                pattern: '',
                addUserField: false,
                addUserFieldMap: '',
                enableUnique: false,
                required: false,
                enableIntl: 1,
                icon:'',
                width:12,
            },
            url: {
                label: 'URL',
                type: 'url',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                pattern: '',
                addUserField: false,
                addUserFieldMap: '',
                enableUnique: false,
                required: false,
                icon:'',
                width:12,
            },
            email: {
                label: 'Email',
                type: 'email',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                maxlength: '',
                masking: '',
                pattern: '',
                addUserField: false,
                addUserFieldMap: '',
                enableUnique: false,
                required: false,
                icon:'',
                width:12,
            },
            number: {
                label: 'Number',
                type: 'number',
                className: 'form-control',
                name: '',
                value: '',
                description: '',
                placeholder: '',
                pattern: '',
                addUserField: false,
                addUserFieldMap: '',
                enableUnique: false,
                required: false,
                min: '',
                max: '',
                icon:'',
                width:12,
            },
            hidden: {
                name: '',
                label: 'Hidden',
                value: '',
                type: 'hidden'
            },
            separator: {
                label: 'Separator',
                type: 'separator',
                className: 'spacer',
                dataRefLabel: '',
                dataRefId: '',
                height: '',
                customType: 'spacer',
                dataNonInput: 1,
                width:12,
            },
            header: {
                label: 'Heading',
                type: 'header',
                subtype: 'h1',
                dataRefLabel: '',
                dataRefId: '',
                dataNonInput: 1,
                className: '',
                width:12,
            },
            date: {
                label: 'Date',
                type: 'date',
                description: '',
                placeholder: '',
                min: '',
                max: '',
                dataDateFormat: 'mm/dd/yy',
                className: 'form-control',
                name: '',
                addUserField: false,
                addUserFieldMap: '',
                required: false,
                icon:'',
                width:12,
                value: ''
            },
            file: {
                label: 'File Upload',
                type: 'file',
                description: '',
                className: 'form-control',
                name: '',
                addUserField: false,
                addUserFieldMap: '',
                accept: '',
                required: false,
                icon:'',
                width:12,
            },
            username: {
                type: 'username',
                required: true,
                label: 'Username',
                description: '',
                placeholder: '',
                className: 'form-control',
                pattern: '',
                maxlength: '',
                name: '',
                addUserField: false,
                addUserFieldMap: '',
                icon:'',
                width:12,
            },
            select: {
                label: 'Select',
                type: 'select',
                values: [],
                className: 'form-control',
                name: '',
                description: '',
                placeholder: '',
                addUserField: false,
                addUserFieldMap: '',
                required: false,
                multiple: false,
                name: '',
                icon:'',
                width:12,
                value:'',
            },
            radio_group: {
                type: 'radio-group',
                label: 'Radio Group',
                name: '',
                required: false,
                inline: false,
                other: false,
                description: '',
                user_roles: false,
                className: 'form-control',
                addUserField: false,
                addUserFieldMap: '',
                values: [],
                icon:'',
                multiple: false,
                width:12,
                value:'',
            },
            checkbox_group: {
                type: 'checkbox-group',
                label: 'Checkbox Group',
                name: '',
                required: false,
                inline: false,
                other: false,
                description: '',
                className: 'form-control',
                addUserField: false,
                addUserFieldMap: '',
                values: [],
                icon:'',
                multiple: true,
                width:12,
                value: '',
            }
        },

    },
    watch: {},
    components: {vuedraggable},
    computed:{
      inputFields(){
          input = [];
          for(i=0;i<this.fieldTypes.length;i++){
              field = this.fieldTypes[i];
              if(field['cat']=='input'){
                  input.push(field);
              }
          }
          return input;
      },
      displayFields(){
          input = [];
          for(i=0;i<this.fieldTypes.length;i++){
              field = this.fieldTypes[i];
              if(field['cat']=='display'){
                  input.push(field);
              }
          }
          return input;
      } 
    },
    methods: {
        saveForm(close) {
            $ = jQuery;
            var loader = $("#erf_progress");
            close = typeof close === 'undefined' ? false : true;
            var req = {
                action: 'erf_save_form',
                data: {title: this.title,fields: JSON.stringify(this.fields),id: erf_data.form_data.id}

            };
            //var ul = $('#erf-form-builder ul.ui-sortable');
            //ul.find('li').removeClass('erf-label-error'); // Remove error class for all fields.
            loader.show();
            $.post(ajaxurl,req,function (res) {
                if (res.success) {
                    if (res.data.redirect && close) {
                        window.location.href = res.data.redirect;
                    }
                } else
                {
                    if (res.success === false) {
                        var emptyLabelError = false;
                        if (res.hasOwnProperty('data') && res.data.hasOwnProperty('errors')) {
                            for (var i = 0; i < res.data.errors.length; i++) {
                                if (res.data.errors[i].hasOwnProperty('field_index')) {
                                    emptyLabelError = true;
                                    //ul.find('li.form-field:eq(' + res.data.errors[i].field_index + ')').addClass('erf-label-error');
                                }
                            }
                        }
                        if (emptyLabelError) {
                            alert('Please make sure to provide valid labels for all the fields.');
                        }
                    }
                }
                loader.hide();
            }).fail(function (xhr, textStatus, e) {
                console.log('Unable to save form data.',xhr.responseText);
                loader.hide();
            });
        },
        onDragDrop(ev) {
            if (ev.hasOwnProperty('added')) {
                field = ev.added.element;
                index = ev.added.newIndex;
                if (field.type == 'address') {
                    this.removeField(index);
                    this.addAddress(index);
                }
            }
        },
        cloneField(field) {
            if (!this.schema.hasOwnProperty(field.key)) {
                return {'type': field.key}
            }
            return this.add(field.key);
        },
        add(type) {
            /*
             * Avoid adding Username field if it is already added.
             */
            if (type == 'username') {
                for (var i = 0; i < this.fields.length; i++) {
                    field = this.fields[i];
                    if (field.type == 'username') {
                        alert('Username already exists');
                        return;
                    }
                }
            }
            return this.addField(type);
        },

        /*
         * 
         * @param {string} type
         * @param {integer|Optional} index
         * @returns {Object|fieldSchema}
         * @description Add new field by type (eg: textbox,textarea,username)
         */
        addField(type, index) {
            fieldSchema = JSON.parse(JSON.stringify(this.schema[type])); //Cloning default field attributes from schema
            fieldSchema = this.prepareFieldObject(fieldSchema,true);
            setTimeout(function () {
                fieldSchema.isNew = false;
                delete fieldSchema['isNew'];
            }, 10000);
            if (typeof index !== 'undefined') {
                this.fields.splice(index, 0, fieldSchema);
            } 
            return fieldSchema;
        },

        /*
         * 
         * @param {integer} index
         * @returns {void}
         * @description Removes field by index.
         */
        removeField(index) {
            this.$delete(this.fields, index);
        },

        /*
         * 
         * @returns {void}
         * @description Created 5 fields (from existing field types) for Country,State,City,Street1 and Street2.
         *              Also sets default attribute values for each of them.
         */
        addAddress(startIndex) {
            country = this.addField('select', startIndex);
            state = this.addField('select', startIndex + 1);
            city = this.addField('text', startIndex + 2);
            street1 = this.addField('text', startIndex + 3);
            street2 = this.addField('text', startIndex + 4);
            zip = this.addField('text', startIndex + 5);
            country.label = 'Country';
            country.type = 'country';
            state.label = 'State / Province';
            state.type = 'state';
            city.label = 'City';
            city.type = 'city';
            street1.label = 'Street Address1';
            street1.type = 'street1';
            street2.label = 'Street Address2';
            street2.type = 'street2';
            zip.type='zip';
            zip.label='Postcode / Zip';
        },

        /*
         * 
         * @param {integer} index
         * @returns {void}
         * @description Add field at a certain index
         */
        addFieldOnIndex(index, field) {
            this.fields.splice(index, 0, field);
        },
        /*
         * 
         * @param {string} attr
         * @param {string|number|Object} value
         * @param {integer} index
         * @returns {void}
         * @description Invoked when field attribute is modified for a child field.
         */
        onUpdate(attr, value, index) {
            console.log(attr,value);
            this.fields[index][attr] = value;
        },

        /*
         * 
         * @param {Object} option
         * @param {integer} index
         * @returns {void}
         * @description Invoked when option is added.
         */
        onOptionAdd(option, index) {
            this.fields[index]['values'].push(option);
        },

        /*
         * 
         * @param {integer} optionIndex
         * @param {integer} index
         * @returns {void}
         * @description Invoked when option is removed.
         */
        onOptionRemove(optionIndex, index) {
            this.fields[index]['values'].splice(optionIndex, 1);
        },

        /*
         * 
         * @param {string} value
         * @param {integer} optionIndex
         * @param {string} type
         * @param {integer} index
         * @returns {void}
         * @description Called when Option attribute ('label','value' or 'selected') is updated.
         */
        onOptionUpdate(value, optionIndex, type, index) {
            this.fields[index]['values'][optionIndex][type] = value;
            console.log('Option Updated',this.fields[index]['values']);
        },
        onDeleteField(index) {
            var deleteField = confirm(erf_data.text_helpers.deleteFieldConfirm);
            if(deleteField){
               this.fields.splice(index, 1);
            }
        },
        onEditField(index) {
            this.dragDisabled = true;
        },
        onDuplicateField(index) {
            newField = JSON.parse(JSON.stringify(this.fields[index])); //Cloning default field attributes from schema
            newField = this.prepareFieldObject(newField,true);
            this.fields.splice(index + 1, 0, newField);
            setTimeout(function () {
                delete newField['isNew'];
            }, 10000);
        },
        onHideEdit() {
            this.dragDisabled = false;
        },
        prepareFieldObject(field,isNew){
            isNew = typeof isNew==='undefined' ? false : true;
            // Attaching uniqueId
            if(!field.hasOwnProperty('uniqueID')){
                field.uniqueID = 'unique-' + erforms_random_str(15);
            }
            
            if(isNew){
                if (field.hasOwnProperty('name')) { // Generating random name for each field.
                    field.name = 'field-' + erforms_random_str(15);
                }

                if (field.hasOwnProperty('dataRefLabel')) { // Generating random reference IDs and labels for non input fields
                    field.dataRefLabel = 'ref-' + erforms_random_str(8);
                    field.dataRefId = 'ref-' + erforms_random_str(8);
                }
                field.isNew = true;
            }
            return field;
        }
    },
    mounted() {
        // Initializing all the existing fields.
        for (var i = 0; i < erf_data.form_data.fields.length; i++) {
            field = this.prepareFieldObject(erf_data.form_data.fields[i]);
            this.fields.push(field);
        }   
    }
});

/*
 * 
 * <erf_field_actions> tag.
 * Supported actions:
 * 1. Edit field settings
 * 2. Delete field.
 * 3. Duplicate field settings.
 */
Vue.component('erf_field_actions', {
    data: function () {
        return {}
    },
    template: '#erf_field_actions',
    methods: {
        editField() {
            this.$emit('edit', this.fieldIndex);
        },
        deleteField() {
            this.$emit('delete', this.fieldIndex);
        },
        duplicateField() {
            this.$emit('duplicate', this.fieldIndex);
        }
    },
    watch: {},
    computed: {
        editAllowed: function () {
            return true;
        },
        deletionAllowed: function () {
            if(this.attrs.type=='button' && this.attrs.subtype=='submit')
            {
                return false;
            }
            return ['user_email','password'].indexOf(this.attrs.type)>=0 ? false : true;
        },
        duplicateAllowed: function () {
            if(this.attrs.type=='button' && this.attrs.subtype=='submit')
            {
                return false;
            }
            return ['username','user_email','password'].indexOf(this.attrs.type)>=0 ? false : true;
        }
    },
    props: ['fieldIndex','attrs']
});

/*
 * <erf_meta_options> tag.
 * 1. Enable user meta 
 * 2. Meta map binding.
 */
Vue.component('erf_meta_options', {
    data: function () {
        return {
            showMapField: false,
            addUserFieldHelptext: '',
            addUserFieldMapHelptext: ''
        }
    },
    template: '#erf_meta_options',
    methods: {
        onAddUserFieldUpdate(value) {
            this.$emit('update', value, 'addUserField');
            this.showMapField = value;
        },
        onAddUserFieldMapUpdate(value) {
            this.$emit('update', value, 'addUserFieldMap');
        }
    },
    created() {
        this.addUserFieldHelptext = erf_data.text_helpers['addUserField'];
        this.addUserFieldMapHelptext = erf_data.text_helpers['addUserFieldMap'];
        this.showMapField = this.addUserField;
    },
    props: ['addUserField', 'addUserFieldMap']
});

/*
 * <erf_conf_pwd_options> tag.
 * 1. Enable confirm password. 
 * 2. Password Label
 */
Vue.component('erf_conf_pwd_options', {
    data: function () {
        return {
            showLabel: false
        }
    },
    template: '#erf_conf_pwd_options',
    methods: {
        onConfirmPassword(value) {
            this.$emit('update', value, 'confirmPassword');
            this.showLabel = value;
        },
        onLabelUpdate(value) {
            this.$emit('update', value, 'confirmPasswordLabel');
        }
    },
    computed: {
        helptext: function () {
            return erf_data.text_helpers['confirmPassword'];
        }
    },
    created: function () {
        if(this.confirmPassword){
            this.showLabel= this.confirmPassword;
        }
    },
    props: ['confirmPassword', 'confirmPasswordLabel']
});

/*
 * <erf_richtext_options> tag.
 * 1. Richtext editing of content.
 */
Vue.component('erf_richtext_options', {
    data: function () {
        return {
            uid: '',
            editorCreated: false
        }
    },
    template: '#erf_richtext_options',
    methods: {
        createEditor: function () {
            var self = this;
            wp.editor.initialize(this.uid, {
                tinymce: {
                    setup: function (ed) {
                        ed.onChange.add(function (ed, l) {
                            self.$emit('update', ed.getContent(), 'label')
                        });
                    },
                    wpautop: true
                },
                quicktags: true, mediaButtons: true

            });
            self.editorCreated = true;
        }
    },
    mounted() {
        this.uid = "message" + this._uid;
    },
    updated() {
        this.$nextTick(function () {
            if (!this.editorCreated) {
                this.createEditor();
            }
        });
    },
    computed: {
        helptext: function () {
            return erf_data.text_helpers['richtext'];
        }
    },
    destroyed() {
        if (!this.editorCreated) {
            wp.editor.remove(this.uid);
        }
    },
    props: ['value']
});



/*
 * <erf_select_options> tag.
 * @description Allows to add multiple options in Dropdown,Checkbox,Radio
 */
Vue.component('erf_select_options', {
    data: function () {
        return {
            allowMultiple: false,
            showValuesEditor: false,
            sourceValues:'',
        }
    },
    components: {vuedraggable},
    template: '#erf_select_options',
    methods: {
        importBulkValues(){
            this.$emit('update',[], 'values');
            var lines = this.sourceValues.replace(/^\n|\n$/g, '').split('\n');
            for(var i = 0;i < lines.length;i++){
                var op = lines[i].split(',');
                if(typeof op[1] === 'undefined'){
                    op.push(op[0]);
                }
                var option = {label: op[0].trim(), value: op[1].trim(), selected: false,uniqueID: 'option-' + erforms_random_str(15)};
                this.$emit('optionAdd',option);
            }
            this.showValuesEditor = false;
        },
        onOptionDefaultUpdate(value,optionIndex,t){
            if(t=='single'){
                // Updating selected property to false for all the options
                for(var i=0;i<this.options.length;i++){
                    this.options[i].selected = optionIndex==i ? true: false;
                }
                this.$emit('update', this.options, 'values');
            }
            else{
                this.$emit('optionUpdate', value, optionIndex, 'selected');
            }
            
        },
        onLabelUpdate(value, optionIndex) {
            this.$emit('optionUpdate', value, optionIndex, 'label');
        },
        onValueUpdate(value, optionIndex) {
            this.$emit('optionUpdate', value, optionIndex, 'value');
        },
        onBulkValuesImport(value){
            this.sourceValues = value;
        },
        onMultipleUpdate(value) {
            this.$emit('update', value, 'multiple');
            this.allowMultiple = value;
            // Updating selected property to false for all the options
            for(var i=0;i<this.options.length;i++){
                this.options[i].selected = false;
            }
            this.$emit('update', this.options, 'values');
        },
        addValue() {
            this.$emit('optionAdd', this.prepareOption(true,''));
        },
        removeValue(index) {
            this.$emit('optionRemove', index);
        },
        prepareOption(isNew,option){
            isNew = typeof isNew==='undefined' ? false : isNew;
            if(isNew){
                return {label: 'Label', value: 'Value', selected: false,uniqueID: 'option-' + erforms_random_str(15)};
            } else{
                if(!option.hasOwnProperty('uniqueID')){
                    option.uniqueID = 'option-' + erforms_random_str(15);
                    return option;
                }
            }
        }
    },
    created: function () {
        if (this.options.length == 0) {
            this.$emit('update', [this.prepareOption(true,'')], 'values');
        }
        else{
            for(var i=0;i<this.options.length;i++){
                if(!this.options[i].hasOwnProperty('uniqueID')){
                    this.options[i] = this.prepareOption(false,this.options[i]);
                }
            }
        }
        this.allowMultiple = this.multiple;
    },
    props: ['multiple', 'options', 'type']
});

/*
 * <erf_display_options> tag.
 * @description Displays all the relevant field options.
 */
Vue.component('erf_display_options', {
    data: function () {
        return {}
    },
    template: '#erf_display_options',
    methods: {
        onUpdate(value, attr) {
            this.$emit('update', value, attr);
        }
    },
    computed: {
        showDateFormat: function(){
            return this.attrs.type == 'date' ? true : false;
        },
        showPlaceholder: function(){
            return this.attrs.type == 'file' ? false : true;
        },
        showIntl: function(){
            return this.attrs.type == 'tel' ? true : false;
        },
        showConfirmPassword: function(){
            return this.attrs.type == 'password' ? true : false;
        },
        showRows: function(){
            return this.attrs.type == 'textarea' ? true : false;
        },
        showCols: function(){
            return this.attrs.type == 'textarea' ? true : false;
        },
        showClassName: function(){
            return true;
        },
        showDefValue: function(){
            return ['text','url','email','number','hidden','country','city','state','street1','street2','zip'].indexOf(this.attrs.type)>=0 ? true : false;
        },     
    },
    created: function () {},
    props: ['attrs']
});

/*
 * <erf_validation_options> tag.
 * @description Displays all the relevant field options.
 */
Vue.component('erf_validation_options', {
    data: function () {
        return {}
    },
    template: '#erf_validation_options',
    methods: {
        onUpdate(value, attr) {
            this.$emit('update', value, attr);
        }
    },
    computed: {
        showRequired: function () {
            return ['username', 'user_email', 'password'].indexOf(this.attrs.type) >= 0 ? false : true;
        },
        showMaxlength: function () {
            return ['textarea','text','tel','url','email','username','user_email','password','city','street1','street2','zip'].indexOf(this.attrs.type) >= 0 ? true : false;
        },
        showPattern: function () {
            return ['text','tel','url','email','username','user_email','password','city','street1','street2','zip'].indexOf(this.attrs.type) >= 0 ? true : false;
        },
        showMasking: function () {
            return ['text','tel','zip'].indexOf(this.attrs.type) >= 0 ? true : false;
        },
        showMinDate: function () {
            return this.attrs.type == 'date' ? true : false;
        },
        showMaxDate: function () {
            return this.attrs.type == 'date' ? true : false;
        },
        showMin: function () {
            return this.attrs.type == 'number' ? true : false;
        },
        showMax: function () {
            return this.attrs.type == 'number' ? true : false;
        },
        showAccept: function () {
            return this.attrs.type == 'file' ? true : false;
        },
    },
    props: ['attrs']
});

/*
 * <erf_adminstrator_options> tag.
 * @description Displays all the relevant field options.
 */
Vue.component('erf_administrator_options', {
    data: function () {
        return {}
    },
    template: '#erf_administrator_options',
    methods: {
        onUpdate(value, attr) {
            this.$emit('update', value, attr);
        }
    },
    computed: {
        showMeta: function () {
            return ['username', 'user_email', 'password'].indexOf(this.attrs.type) >= 0 ? false : true;
        },
        showUnique: function () {
            if(['number','date','text'].indexOf(this.attrs.type)>=0){
                return true;
            } else{
                return false;
            }
        }
    },
    props: ['attrs']
});


/*
 * <erf_btn_style_options> tag.
 * @description Change button classes.
 */
Vue.component('erf_btn_style_options', {
    data: function () {
        return {}
    },
    template: '#erf_btn_style_options',
    methods: {
        changeButtonStyle(value) {
            this.$emit('update', value, 'className');
        },
    },
    mounted() {},
    props: []
});



Vue.component('erf_field_helptext', {
    data: function () {
        return{
            showMessage: false
        }
    },
    template: '#erf_field_helptext',
    methods: {},
    watch: {},
    mounted() {},
    props: ['message']
});

/*
 * <erf_field_option> tag.
 * @description Common field option tag.
 */
Vue.component('erf_field_option', {
    data: function () {
        return {}
    },
    template: '#erf_field_option',
    methods: {
        onUpdate(value, attr) {
            this.value= value;
            this.$emit('update', value, attr);
        },
        onMouseup(el){
          $(el).blur();
        }
    },
    watch: {},
    computed: {
        helptext: function () {
            return erf_data.text_helpers[this.type];
        }
    },
    props: ['type', 'value']
});

/*
 * <erf_field> tag.
 * @description Common field tag.
 */
Vue.component('erf_field', {
    data: function () {
        return {
            showOptions: false,
            attrs: {},
        }
    },
    template: '#erf_field',
    watch: {},
    methods: {
        onUpdate(value, attr) {
            this.$emit('update', attr, value, this.fieldIndex);
        },
        onEditField(index) {
            this.showOptions = true;
            this.$emit('edit', index);
        },
        onDeleteField(index) {
            this.$emit('delete', index);
        },
        onDuplicateField(index) {
            this.$emit('duplicate', index);
        },
        hideOptions() {
            this.showOptions = false;
            this.$emit('hide-edit');
        }
    },
    created: function () {
        this.attrs = this.propAttrs;
    },
    computed: {
        type: function () {
            if (['text','tel','url','email','username','user_email','password','city','street1','street2','zip'].indexOf(this.attrs.type)>=0) {
                return 'text';
            }
            else if(['country','state','select','radio-group','checkbox-group'].indexOf(this.attrs.type)>=0){
                return 'select';
            }
            else{
                return this.attrs.type;
            }
        },
        containerClass: function(){
            return 'erf-' + this.attrs.type + '-field'; 
        }
    },
    props: ['propAttrs', 'fieldIndex']
});

/*
 * <erf_select_field> tag.
 * @description Tag to create select field options.
 */
Vue.component('erf_select_field', {
    data: function () {
        return {
            showOptions: false,
            attrs: {},
        }
    },
    template: '#erf_select_field',
    watch: {},
    computed: {
        showSelectOptions: {
            get: function () {
                    if(['country','state'].indexOf(this.attrs.type)>=0){
                        return false;
                    }
                    return true;
                 },
            set: function(newValue){} // Empty setter as type information won't change from user interface     
        },
        containerClass: function(){
            return 'erf-' + this.attrs.type + '-field'; 
        }
    },
    methods: {
        onOptionUpdate(value, optionIndex, type) {
            this.$emit('option-update', value, optionIndex, type, this.fieldIndex);
        },
        onUpdate(value, attr) {
            this.$emit('update', attr, value, this.fieldIndex);
        },
        onMultiple(value) {
            this.$emit('multiple', value, 'multiple');
        },
        onOptionAdd(option) {
            this.$emit('option-add', option, this.fieldIndex);
        },
        onOptionRemove(index) {
            this.$emit('option-remove', index, this.fieldIndex);
        },
        onEditField(index) {
            this.showOptions = true;
            this.$emit('edit', index);
        },
        onDeleteField(index) {
            this.$emit('delete', index);
        },
        onDuplicateField(index) {
            this.$emit('duplicate', index);
        },
        hideOptions() {
            this.showOptions = false;
            this.$emit('hide-edit');
        }
    },
    created: function () {
        this.attrs = this.propAttrs;
    },
    props: ['propAttrs', 'fieldIndex']
});
Vue.component('erf_date_picker', {
    template: '<input v-bind:value="value" v-on:input="onUpdate($event.target.value)" class="fld-min form-control erf_datepicker"/>',
    props: ['dateFormat', 'attr', 'value'],
    mounted: function () {
        $ = jQuery;
        var self = this;
        $(this.$el).datepicker({
            dateFormat: this.dateFormat,
            changeYear: true,
            changeMonth: true,
            yearRange: '-100:+20',
            onSelect: function (date) {
                self.$emit('update', date, self.attr);
            }
        });
    },
    beforeDestroy: function () {
        $(this.$el).datepicker('hide').datepicker('destroy');
    },
    methods:{
        onUpdate(value){
            this.$emit('update', value, this.attr);
        }
    }
});

Vue.component('erf_font_awesome_option', {
    template: '<input type="text" v-bind:value="value" class="fld-icon form-control" />',
    props: ['value'],
    methods: {},
    mounted: function () {
        $ = jQuery;
        var self = this;
        $(this.$el).iconpicker();
        $(this.$el).on('iconpickerSelected', function(event){
            self.$emit('update',event.iconpickerValue,'icon');
        });
    },
});

Vue.component('erf_user_role_option', {
    template: '#erf_user_role_option',
    props: ['value'],
    methods: {
        onUpdate(val){
            if(val){
                values= [];
                for(i=0;i<erf_data.roles.length;i++){
                    values.push({label: erf_data.roles[i].name, value: erf_data.roles[i].role, selected: false})
                }
                this.$emit('update',values,'values');
            }
            this.$emit('update',val,'user_roles');
        }
    },
    computed: {
        helptext: function () {
            return erf_data.text_helpers['user_roles'];
        }
    },
    mounted: function () {},
});

//console.log(erf_data.countries);