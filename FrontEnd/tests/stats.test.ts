import { mount } from "@vue/test-utils";
import { describe, it, expect, vi, beforeEach } from "vitest";
import StatsDistances from "@/views/Stats.vue";
import { Chart } from "chart.js/auto";
import { ApiService } from "@/services/Api.ts";



vi.mock("chart.js/auto", () => {
    const mockChart = vi.fn(function () {
        this.destroy = vi.fn();
    });

    return {
        Chart: mockChart,
    };
});


vi.mock("@/services/Api.ts", () => {
    return {
        ApiService: {
            getStats: vi.fn(),
        },
    };
});



describe("StatsDistances.vue", () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it("charge les données et crée un graphique", async () => {

        ApiService.getStats.mockResolvedValueOnce({
            data: {
                items: [
                    { analyticCode: "passager", totalDistanceKm: 341 },
                    { analyticCode: "autre", totalDistanceKm: 125 },
                    { analyticCode: "fret", totalDistanceKm: 52 },
                    { analyticCode: "support", totalDistanceKm: 78 },
                ],
            },
        });

        const wrapper = mount(StatsDistances);

        await wrapper.vm.$nextTick();

        expect(ApiService.getStats).toHaveBeenCalled();

        expect(Chart).toHaveBeenCalledTimes(1);

        const vm = wrapper.vm as any;
        expect(vm.distances.length).toBe(4);
    });

    it("change le type de graphique (bar → pie)", async () => {

        ApiService.getStats.mockResolvedValueOnce({
            data: {
                items: [
                    { analyticCode: "passager", totalDistanceKm: 341 },
                    { analyticCode: "autre", totalDistanceKm: 125 },
                    { analyticCode: "fret", totalDistanceKm: 52 },
                    { analyticCode: "support", totalDistanceKm: 78 },
                ],
            },
        });

        const wrapper = mount(StatsDistances);

        await wrapper.vm.$nextTick();

        expect(wrapper.vm.chartType).toBe("bar");

        await wrapper.find(".chart-switch button:nth-child(2)").trigger("click");

        expect(wrapper.vm.chartType).toBe("pie");

        expect(Chart).toHaveBeenCalled();
    });
});
