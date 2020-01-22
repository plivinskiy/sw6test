import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import Iterator from 'src/helper/iterator.helper';
import NativeEventEmitter from 'src/helper/emitter.helper';

export default class SwpaCustomOptions extends Plugin {
    static options = {

    };

    init() {
        this._getForm();
        this.initLineItemsIdElement();
        this.initEvents();
        this.updateLineItemId();
        this.$emitter.subscribe('beforeFormSubmit',(e) => {
            this.updateLineItemId(e);
        });
    }

    initEvents(){
        if(!this.optionElements){
            return;
        }
        Iterator.iterate( this.optionElements, option => {
            option.addEventListener('change', this.onOptionChange.bind(this));
        });
        this.calculate();
    }

    onOptionChange(e) {
        const targetOptionElement = new NativeEventEmitter(e.target);

        targetOptionElement.publish('onChangeCustomOption', {me:this, element:this.getOptionElement(targetOptionElement.el)} );

        this.calculate();

        this.updateLineItemId();
    }

    getOptionElement(el){
        const element = [];
        switch (el.tagName) {
            case 'SELECT' : Array.from(el.options).forEach((option,index)=>{ if(option.selected)element[index] = option; }); break;
            default : element[0] = el; break;
        }

        return element;
    }

    get optionElements(){

        return DomAccess.querySelectorAll(document, '[data-option-id]', false);
    }


    initLineItemsIdElement(){
        this.lineItems = Array.from(DomAccess.querySelectorAll(this._form, '[name*=lineItems]', false));
        this.lineItemIdElement = this.lineItems.find(item => {
            const name = DomAccess.getAttribute(item,'name');
            return name.includes('[id]');
        });
    }

    updateLineItemId(){
        if(!this.optionElements){
            return;
        }
        if(this.lineItemIdElement===undefined){
            throw new Error('Can not found lineItems ID element');
        }
        if(this.lineItemIdElementValue === undefined ){
            this.lineItemIdElementValue = this.lineItemIdElement.value;
        }
        this.lineItemIdElement.setAttribute('value',this.lineItemIdElementValue);
        Iterator.iterate( this.optionElements, option => {
            const elements = this.getOptionElement(option);
            elements.forEach(element=>{
                if(option.value !==undefined && this.isOptionSelected(element)){
                    this.lineItemIdElement.setAttribute('value',this.lineItemIdElement.value + '+' + option.value);
                }
            });
        });
    }

    _getForm() {
        if (this.el && this.el.nodeName === 'FORM') {
            this._form = this.el;
        } else {
            this._form = this.el.closest('form');
        }
    }

    calculate() {
        console.log('calculate');
        const priceElements = DomAccess.querySelectorAll(document,'[data-price-update]',false);
        if(!priceElements){
            return;
        }

        Iterator.iterate( priceElements, element => {
            const originalPrice = element.getAttribute('data-original'),
                currency = element.getAttribute('data-original-currency');

            var price = parseFloat(originalPrice);
            Iterator.iterate( this.optionElements, option => {
                const optionElements = this.getOptionElement(option);
                optionElements.forEach(optionElement=>{
                    if(optionElement===undefined){
                        return;
                    }
                    const optionPrice = optionElement.getAttribute('data-price') | 0,
                        optionPriceType = optionElement.getAttribute('data-type');

                    if( this.isOptionSelected(optionElement) ){
                        if( optionPriceType === 'fixed' ){
                            price = price + parseFloat(optionPrice);
                        } else {
                            price = price + ((price * parseFloat(optionPrice))/100);
                        }
                    }
                    element.innerHTML = ( price.toFixed(2) ) + ' ' + currency;
                });
            });
        });
    }

    isOptionSelected(element){
        let isOptionElementSelected = false;

        if(element.tagName === 'INPUT' ){
            switch (element.getAttribute('type')) {
                case 'checkbox': isOptionElementSelected = element.checked?true:false; break;
                case 'radio': isOptionElementSelected = element.checked?true:false; break;
                case 'text': isOptionElementSelected = element.value?true:false; break;
            }
        } else {
            isOptionElementSelected = element.selected && element.value.length>0;
        }

        return isOptionElementSelected;
    }
}
