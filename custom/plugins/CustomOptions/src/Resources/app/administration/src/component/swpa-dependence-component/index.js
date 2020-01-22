import template from './swpa-dependence-component.html.twig';

const { Component, Mixin, Filter, Context } = Shopware;
const Criteria = Shopware.Data.Criteria;

Component.register('swpa-dependence-component', {
    inject: ['repositoryFactory'],
    template,

    props: {
        option: {
            type: Object,
            required: true
        },
        value: {
            type: Object,
            required: true
        },
        displayWindow: {
            required: true
        },
        closeComponent: {
            required: true
        },
        productId: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            visible: false,
            options: {},
            gridColumns:[
                            {
                                property: 'title',
                                label: this.$tc('swpa-options.column.title'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                width: '80%'
                            }, {
                                property: 'isAvailable',
                                label: this.$tc('swpa-options.dependence.column.is_available'),
                                visible: true,
                                allowResize: true,
                                primary: true,
                                rawData: false,
                                // width: '200px'
                            }
                        ]
        };
    },

    created() {
        this.createdComponent();
    },

    watch: {
        displayWindow() {
            if (this.displayWindow && !this.visible) this.show()
            else if (!this.displayWindow && this.visible) this.hide()
        }
    },

    computed: {
        title(){
            return 'Dependence of the value: ' + this.value.title;
        },

        repositoryOptions() {
            return this.repositoryFactory.create('product_custom_option');
        },

        repositoryValues() {
            return this.repositoryFactory.create('product_custom_option_type_value');
        },
        columns(){

            return this.gridColumns;
        }
    },

    methods: {
        createdComponent() {

        },
        checkItemAvailability(option,item) {

            return false;
        },

        onSave(e){
            const elements = document.querySelectorAll('[name*="dependence"]');
            this.value.dependence = new Array();
            elements.forEach((item,index)=>{
                this.value.dependence[index]={'name':item.name ,'value': item.value};
            });
            this.saveValue(this.value);
            this.onCloseModal();
        },

        onChangeDependence(item){

            return this.saveValue(item);
        },

        saveValue(value){
            return new Promise((resolve) => {
                this.repositoryValues.save(value, Context.api).then((response) => {
                    console.log('success',response);
                    resolve('success');
                }).catch((response) => {
                    resolve(response);
                    console.log('error',response);
                });
            });
        },

        hasValues(option) {

            return option.values !== undefined && option.values.length>0;
        },

        isOptionAvailable(option) {

            return option.id !== this.option.id;
        },

        onChangeAvailability(item){
            console.log(item);
        },

        onCloseModal() {
            this.hide();
            this.closeComponent(this.option, this.value);
        },

        hide() {

            this.visible = false
        },

        show() {

            this.load();
            this.visible = true
        },

        getValueById(id){
            return new Promise((resolve)=>{
                this.repositoryValues.get(id, Context.api).then((result) => {
                     resolve(result);
                 });
            });
        },

        load() {

            this.loadOptions();
        },

        async loadOptions() {
            const criteria = new Criteria();

            criteria.addAssociation('values');
            criteria.addFilter(Criteria.equals('product_id', this.productId));

            await this.repositoryOptions.search(criteria, Context.api).then((options) => {
                this.options = options;
            }).catch(error=>{
                console.error(error);
            });
            return true;
        }
    }

});
