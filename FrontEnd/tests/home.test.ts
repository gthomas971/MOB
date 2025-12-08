import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach } from "vitest";
import HomeView from "@/views/Home.vue";
import SearchForm from "@/components/itinerary/IteneraryForm.vue";
import Itinerary from "@/components/itinerary/Itinerary.vue";
import ItineraryHeader from "@/components/itinerary/ItineraryHeader.vue";
import { useStationStore } from "@/stores/useStation.ts";
import { ApiService } from "@/services/Api.ts";
import { nextTick } from "vue";
import InversionSvg from "*.svg";

vi.mock("@/stores/useStation.ts", () => {
    return {
        useStationStore: vi.fn(() => ({
            stations: [
                { id: 1, long_name: "Paris", short_name: "PRS" },
                { id: 2, long_name: "Lyon", short_name: "LYN" },
                { id: 3, long_name: "Marseille", short_name: "MRS" }
            ]
        }))
    };
});

vi.mock("@/services/Api.ts", () => {
    return {
        ApiService: {
            getRoute: vi.fn()
        }
    };
});

describe("Home.vue", () => {
    let stationStore: any;

    beforeEach(() => {
        vi.clearAllMocks();
        stationStore = useStationStore();
        stationStore.stations = [];
    });

    it("affiche SearchForm lors du chargement", () => {
        const wrapper = mount(HomeView);

        expect(wrapper.findComponent(SearchForm).exists()).toBe(true);
        expect(wrapper.findComponent(Itinerary).exists()).toBe(false);

        expect(wrapper.vm.showItinerary).toBe(false);
        expect(wrapper.vm.errorMessage).toBe("");
    });


    it("inverse correctement start et end", () => {
        const wrapper = mount(HomeView);

        wrapper.vm.start = "Paris";
        wrapper.vm.end = "Lyon";

        wrapper.vm.inversion();

        expect(wrapper.vm.start).toBe("Lyon");
        expect(wrapper.vm.end).toBe("Paris");
    });


    it("affiche une erreur si start ou end est vide", async () => {
        const wrapper = mount(HomeView);

        wrapper.vm.start = "";
        wrapper.vm.end = "Lyon";

        await wrapper.vm.searchItinerary();

        expect(wrapper.vm.errorMessage)
            .toBe("Entrez votre départ et votre destination.");

        expect(ApiService.getRoute).not.toHaveBeenCalled();
    });


    it("affiche une erreur si les stations n'existent pas", async () => {

        const wrapper = mount(HomeView);

        wrapper.vm.start = "Lille";
        wrapper.vm.end = "Brest";

        await wrapper.vm.searchItinerary();

        expect(wrapper.vm.errorMessage).toBe("Veuillez sélectionner une station valide.");
        expect(ApiService.getRoute).not.toHaveBeenCalled();
    });


    it("refuse un départ et une arrivée identiques", async () => {

        const wrapper = mount(HomeView);

        wrapper.vm.start = "Paris";
        wrapper.vm.end = "Paris";

        await wrapper.vm.searchItinerary();

        expect(wrapper.vm.errorMessage)
            .toBe("Veuillez sélectionner un départ et une arrivée différents.");
    });


    it("ne rappelle pas l'API si même recherche", async () => {
        const wrapper = mount(HomeView);


        wrapper.vm.lastStart = "PRS";
        wrapper.vm.lastEnd = "LYN";

        wrapper.vm.start = "Paris";
        wrapper.vm.end = "Lyon";

        await wrapper.vm.searchItinerary();

        expect(ApiService.getRoute).not.toHaveBeenCalled();
        expect(wrapper.vm.showItinerary).toBe(true);
    });


    it("appelle l'API et bascule vers Itinerary", async () => {
        const wrapper = mount(HomeView);


        ApiService.getRoute.mockResolvedValueOnce({
            data: {
                distanceKm: 120,
                segments: []
            }
        });

        wrapper.vm.start = "Paris";
        wrapper.vm.end = "Lyon";

        await wrapper.vm.searchItinerary();

        expect(ApiService.getRoute).toHaveBeenCalledWith("PRS", "LYN");
        expect(wrapper.vm.showItinerary).toBe(true);
        expect(wrapper.vm.routeData.distanceKm).toBe(120);

        expect(wrapper.findComponent(Itinerary).exists()).toBe(true);
    });


    it("revient à la recherche lorsqu'on appelle backToSearch", () => {
        const wrapper = mount(HomeView);

        wrapper.vm.showItinerary = true;

        wrapper.vm.backToSearch();

        expect(wrapper.vm.showItinerary).toBe(false);
        expect(wrapper.findComponent(SearchForm).exists()).toBe(true);
    });


    it("revient à la recherche lorsqu'on clique sur le bouton du header", async () => {
        // On monte le composant avec le vrai header et le vrai Itinerary
        const wrapper = mount(HomeView, {
            global: {
                components: {
                    ItineraryHeader,
                    Itinerary,
                    SearchForm,
                },
            },
        });

        wrapper.vm.showItinerary = true;
        await wrapper.vm.$nextTick();

        const header = wrapper.findComponent(ItineraryHeader);
        expect(header.exists()).toBe(true);


        const button = header.find("svg");
        await button.trigger("click");

        expect(wrapper.vm.showItinerary).toBe(false);

        const searchForm = wrapper.findComponent(SearchForm);
        expect(searchForm.exists()).toBe(true);
    });

});
