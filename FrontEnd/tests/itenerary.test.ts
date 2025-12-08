import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import ItineraryForm from '@/components/itinerary/IteneraryForm.vue';
import InputAutocomplete from '@/components/form/InputSearch.vue';
import LoaderButton from '@/components/form/LoaderButton.vue';
import InversionSvg from '@assets/svgs/double_direction_arrows.svg';


const stations = [
    { id: 1, long_name: 'Paris', short_name: 'PAR' },
    { id: 2, long_name: 'Lyon', short_name: 'LYO' },
];


describe('ItineraryForm', () => {
    it('Affichage des inputs et événements', async () => {
        const wrapper = mount(ItineraryForm, {
            props: {
                stations,
                start: '',
                end: '',
                loading: false,
                errorMessage: ''
            },
            global: {
                components: {
                    InputAutocomplete,
                    LoaderButton,
                    InversionSvg
                }
            }
        });

        const startInput = wrapper.find('input#start');
        const endInput = wrapper.find('input#end');
        const button = wrapper.find('button');

        expect(startInput.exists()).toBe(true);
        expect(endInput.exists()).toBe(true);
        expect(button.exists()).toBe(true);

        await startInput.setValue('Paris');
        await endInput.setValue('Lyon');

        expect(wrapper.emitted('update:start')[0]).toEqual(['Paris']);
        expect(wrapper.emitted('update:end')[0]).toEqual(['Lyon']);

        await button.trigger('click');

        expect(wrapper.emitted('search')[0]).toEqual(['Paris', 'Lyon']);
    });

    it('Filtrage des suggestions dans InputAutocomplete', async () => {
        const wrapper = mount(InputAutocomplete, {
            props: { label: 'Départ', id: 'start', modelValue: '', stations }
        });
        const input = wrapper.find('input');

        await input.setValue('P');
        expect(wrapper.vm.suggestions).toEqual([]);

        await input.setValue('Pa');
        expect(wrapper.vm.suggestions).toEqual([
            { id: 1, long_name: 'Paris', short_name: 'PAR' }
        ]);
    });

    it('Sélection d’une suggestion', async () => {
        const wrapper = mount(InputAutocomplete, {
            props: { label: 'Départ', id: 'start', modelValue: '', stations }
        });

        wrapper.vm.suggestions = [{ id: 1, long_name: 'Paris', short_name: 'PAR' }];
        await wrapper.vm.$nextTick();

        await wrapper.find('li').trigger('click');
        expect(wrapper.emitted()['update:modelValue'][0]).toEqual(['Paris']);
        expect(wrapper.vm.suggestions).toEqual([]);
    });

    it('Vide les suggestions quand on clique à l’extérieur', async () => {
        const wrapper = mount(InputAutocomplete, {
            props: { label: 'Départ', id: 'start', modelValue: '', stations },
            attachTo: document.body
        });

        wrapper.vm.suggestions = [{ id: 1, long_name: 'Paris', short_name: 'PAR' }];

        await document.dispatchEvent(new MouseEvent('click', { bubbles: true }));
        expect(wrapper.vm.suggestions).toEqual([]);
    });
});
