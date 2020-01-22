import template from './product-detail-custom.html.twig';
import './product-detail-custom.scss';

const { Component, Mixin, Filter, Context } = Shopware;
const Criteria = Shopware.Data.Criteria;
const { mapState, mapGetters } = Component.getComponentHelper();

Component.register('sw-product-detail-custom', {
    template,

    inject: ['repositoryFactory'],

    metaInfo() {
        return {
            title: 'Custom'
        };
    },

    data() {
        return {
            options:      [],
            showDeleteOptionModal: false,
            showDeleteValueModal: false,
            showDeleteValueOptionModal: false,
            totalOptions: 0,
            loading:      true,
            gridColumns: [
                            {
                                property: 'title',
                                label: this.$tc('swpa-options.column.title'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false
                            }, {
                                property: 'price',
                                label: this.$tc('swpa-options.column.price'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                width: '95px'
                            }, {
                                property: 'type',
                                label: this.$tc('swpa-options.column.price_art'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                width: '150px'
                            }, {
                                property: 'sku',
                                label: this.$tc('swpa-options.column.article_number'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                width: '200px'
                            }, {
                                property: 'sort_order',
                                label: this.$tc('swpa-options.column.sort_order'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                width: '95px'
                            }
                        ]
        };
    },

    computed: {
        ...mapState('swProductDetail', [
            'repositoryFactory',
            'product'
        ]),

        ...mapGetters('swProductDetail', [
            'isLoading'
        ]),

        optionsExists() {

            return this.options.length > 0;
        },

        productAvailable(){

            return this.product.id;
        },

        optionRepository() {
            return this.repositoryFactory.create('product_custom_option');
        },

        valueRepository() {
            return this.repositoryFactory.create('product_custom_option_type_value');
        },

        canAddNewOption() {
            return true;
        },

        valueColumns(option) {


            return this.gridColumns;
        },

        fieldTypes(){
            return [
                {
                    title:this.$tc('swpa-options.fieldType.group.select'),
                    options: [
                        {
                            title : this.$tc('swpa-options.fieldType.select.select'),
                            type: 'select'
                        },
                        {
                            title:this.$tc('swpa-options.fieldType.select.radio'),
                            type: 'radio'
                        },
                        {
                            title:this.$tc('swpa-options.fieldType.select.checkbox'),
                            type: 'checkbox'
                        },
                        {
                            title:this.$tc('swpa-options.fieldType.select.multiselect'),
                            type: 'multiselect'
                        },
                        {
                            title:this.$tc('swpa-options.fieldType.select.color'),
                            type: 'color'
                        }
                    ]
                },
                {
                    title: this.$tc('swpa-options.fieldType.group.text'),
                    options: [
                        {
                            title: this.$tc('swpa-options.fieldType.text.field'),
                            type: 'text'
                        }
                    ]
                }
            ];
        }

    },

    beforeCreate() {

    },

    mounted() {
        this.mountedComponent();
    },

    watch: {
        product() {
            this.mountedComponent();
        }
    },

    methods: {

        mountedComponent() {
            if(!this.product.id){
                return;
            }
            const criteria = new Criteria(1, 10);
            criteria.addSorting(Criteria.sort('sortOrder', 'asc'));
            Shopware.State.commit('swProductDetail/setLoading', ['rules', true]);

            criteria.addAssociation('values');
            criteria.addFilter(Criteria.equals('product_id', this.product.id));

            this.optionRepository.search(criteria, Context.api).then((res) => {
                this.options      = res;
                this.totalOptions = res.total;
                Shopware.State.commit('swProductDetail/setLoading', ['rules', false]);
            }).catch((e)=>{
                console.log(e);
                Shopware.State.commit('swProductDetail/setLoading', ['rules', false]);
            });
        },


        onShowDeleteOptionModal(option){
            this.showDeleteOptionModal = option;
        },
        onShowDeleteValueModal(option,value){
            this.showDeleteValueModal = value;
            this.showDeleteValueOptionModal = option;
        },

        onCloseDeleteOptionModal(){
            this.showDeleteOptionModal = false;
        },

        onCloseDeleteValueModal(){
            this.showDeleteValueModal = false;
            this.showDeleteValueOptionModal = false;
        },

        isEditingDisabled(){

        },

        isColorFieldAvailable(option){
            if( option.type==='color' ){
                // this.enableGridColumn('color',this.$tc('swpa-options.column.color'));
                return true;
            }

            return false;
        },

        onDependence(option,value){
            value.dependence = true;
        },

        closeComponentValue(o,i){
            this.options.forEach(option=>{
                if(option.id===o.id){
                    option.values.forEach(value=>{
                        if(value.id===i.id){
                            value.dependence=false;
                        }
                    });
                }
            })
        },

        onDuplicate(option){
            console.log('duplicate', option);
        },

        onDeleteOption(optionID){
            this.options.forEach((option) => {
                if (optionID !== option.id) {
                    return;
                }
                this.optionRepository.delete(option.id,Context.api);
                this.options.remove(option.id);
            });
            this.onCloseDeleteOptionModal();
        },

        onValueDelete(option,value){
            option.values.forEach((item) => {
                if (item.id !== value.id) {
                    return;
                }
                this.valueRepository.delete(value.id,Context.api);
                option.values.remove(value.id);
            });
            this.onCloseDeleteValueModal();
        },

        onAddNewValue(option){
            this.createNewValueByType(option);
        },

        valuesAvailable(option) {

            return option.values !== undefined && option.values.length>0;
        },

        getOptionClass(id) {

            return '';
        },

        onOptionChangeStatus(option){
            this.saveOption(option);
        },

        onOptionSortOrderChange(option) {
            this.saveOption(option);
        },

        onOptionIsRequiredChange(option){
            this.saveOption(option);
        },

        onOptionTitleChange(option) {
            this.saveOption(option);
        },

        onOptionTypeChange(option) {
            if(!option.type){
                option.active = false;
                this.removeAllValues(option);
            } else if(option.values.length < 1){
                this.createNewValueByType(option);
            }

            if(option.type==='color'){
                this.enableGridColumn('color',this.$tc('swpa-options.column.color'))
            } else {
                this.gridColumns.forEach((column,index) => {
                   if(column.property==='color'){
                       this.gridColumns.splice(index, 1);
                   }
                });
            }
            this.saveOption(option);
        },

        enableGridColumn(name,title){
            this.gridColumns.push({
                property: name,
                label: title,
                visible: true,
                allowResize: true,
                primary: true,
                rawData: false
            });
        },

        onAddNewOption(optionId = null) {
            return this.createNewOption(optionId);
        },

        onChangeValue(value){
            return this.saveValue(value);
        },

        createNewOption(optionId = null){
            this.loading = true;
            return new Promise((resolve) => {
                const newOption = this.optionRepository.create(Context.api);
                newOption.product_id = this.product.id;
                this.optionRepository.save(newOption, Context.api).then(() => {
                    this.load(newOption.id).then(() => {
                        this.loading = false;
                        resolve('success');
                    });
                }).catch((response) => {
                    this.loading = false;
                    resolve(response);
                });
            });
        },

        saveOption(option){
            return new Promise((resolve) => {
                this.optionRepository.save(option, Context.api).then((response) => {
                    resolve('success');
                }).catch((response) => {
                    resolve(response);
                });
            });
        },

        saveValue(value){
            return new Promise((resolve) => {
                this.valueRepository.save(value, Context.api).then(() => {
                    resolve('success');
                }).catch((response) => {
                    resolve(response);
                });
            });
        },

        removeAllValues(option){
            return new Promise(resolve => {
                option.values.forEach(value => {
                    this.valueRepository.delete(value.id,Context.api).then(res => {
                        option.values.remove(value.id);
                    });
                });
            });
        },

        canAddNewValue(option){
            if(option.type=='text'){
                return false;
            }
            return true;
        },

        canNotBeActivate( option ){

            return option.type===null || option.title===null || option.values.length <= 0;
        },

        createNewValueByType( option ){
            if( option.values===undefined ) {
                option.values = {};
            }

            return new Promise((resolve) => {
                const newValue = this.valueRepository.create(Context.api);
                newValue.option_id = option.id;
                newValue.title = 'Test Value';

                this.valueRepository.save(newValue, Context.api).then((res) => {
                    this.loadValue(option,newValue.id);
                    resolve(newValue);
                }).catch((response) => {
                    resolve(response);
                });
            });

        },

        loadValue(option,id){
            this.valueRepository.get(id, Context.api).then((res) => {
                option.values.push(res);
            });
        },

        load(id) {
            this.optionRepository.get(id, Context.api).then((res) => {

                this.options.push(res);

                this.$nextTick(() => {
                    const scrollableArea = this.$parent.$el.children.item(0);
                    if (scrollableArea) {
                        scrollableArea.scrollTo({
                            top: scrollableArea.scrollHeight,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
    }
});
