import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

function generateTrackingCode() {
    return Math.random().toString(36).substring(2, 12).toUpperCase();
}

export function useCreateMerger(props) {
    const form = useForm({
        society_id: '',
        sub_option_1: '',
        sub_option_2: '',

        // --- App (From) ---
        registration_no: '',
        from_app_no: '',
        from_security_code: '',
        from_size: '',
        client_name: '',
        client_cnic: '',
        app_type: '',
        payment_plan_plot_price: 0,
        payment_plan_live_id: '',
        payment_plan_down_payment: 0,
        ledger_down_payment: 0,
        ledger_plot_price: 0,
        sum_payment: 0,
        received_downpayment: 0,

        // --- Merge To ---
        merge_to_details: [
            {
                merge_to: '',
                merge_to_no: '',
                to_security_code: '',
                to_size: '',
                merge_app_type: '',
                ledger_amount: '',
                merging_fee: '',
                to_payment_plan_plot_price: '',
                to_payment_plan_live_id: '',
                to_payment_plan_down_payment: '',
            }
        ],

        // --- Client Details ---
        box_no: props.box_no,
        tracking_code: generateTrackingCode(),
        dealer_id: '',
        dealer_phone: '',
        submitter_cnic: '',
    });

    const isFetching = ref(false);
    const isFetchingMergeTo = ref({});

    // Computed properties to handle visibility based on block selection
    const selectedBlockName = computed(() => {
        if (!form.society_id) return '';
        const block = props.blocks.find(b => b.id === form.society_id);
        return block ? block.name : '';
    });

    const showOneExtraDropdown = computed(() => {
        return selectedBlockName.value === 'Awami Greens';
    });

    const showTwoExtraDropdowns = computed(() => {
        return selectedBlockName.value === 'Blue World NAC-06' || selectedBlockName.value === 'Down Town';
    });

    const fileTypeOptions = computed(() => {
        if (selectedBlockName.value === 'Awami Greens') {
            return [
                { value: '1', label: 'By-name' },
                { value: '2', label: 'Open' },
                { value: '3', label: 'Form' }
            ];
        } else if (selectedBlockName.value === 'Blue World NAC-06' || selectedBlockName.value === 'Down Town') {
            return [
                { value: '2', label: 'Open' }
            ];
        }
        return [];
    });

    const mergingTypeOptions = computed(() => {
        if (selectedBlockName.value === 'Blue World NAC-06') {
            return [
                { value: '1', label: 'Commercial' }
            ];
        } else if (selectedBlockName.value === 'Down Town') {
            return [
                { value: '1', label: 'Open Awami Greens' },
                { value: '2', label: 'Sector A' },
                { value: '3', label: 'DT Open Form Merging' }
            ];
        }
        return [];
    });

    const clearAppAndMergeDetails = () => {
        form.registration_no = '';
        form.from_app_no = '';
        form.from_security_code = '';
        form.from_size = '';
        form.client_name = '';
        form.client_cnic = '';
        form.app_type = '';
        form.payment_plan_plot_price = 0;
        form.payment_plan_live_id = '';
        form.payment_plan_down_payment = 0;
        form.ledger_down_payment = 0;
        form.ledger_plot_price = 0;
        form.sum_payment = 0;
        form.received_downpayment = 0;
        form.merge_to_details = [
            {
                merge_to: '',
                merge_to_no: '',
                to_security_code: '',
                to_size: '',
                merge_app_type: '',
                ledger_amount: '',
                merging_fee: '',
                to_payment_plan_plot_price: '',
                to_payment_plan_live_id: '',
                to_payment_plan_down_payment: '',
            }
        ];
    };

    watch(() => form.society_id, () => {
        form.sub_option_1 = '';
        form.sub_option_2 = '';
        clearAppAndMergeDetails();
        form.dealer_id = '';
        form.dealer_phone = '';
        form.submitter_cnic = '';
    });

    watch([() => form.sub_option_1, () => form.sub_option_2], () => {
        clearAppAndMergeDetails();
    });

    const fetchFromAppData = () => {
        if (!form.registration_no || !form.society_id) {
            alert("Please select Block and enter App No");
            return;
        }
        if ((showOneExtraDropdown.value || showTwoExtraDropdowns.value) && !form.sub_option_1) {
            alert("Please select File Type");
            return;
        }
        isFetching.value = true;

        axios.get(route('mergers.fetch-main-app-data'), {
            params: {
                reg_no: form.registration_no,
                society_id: form.society_id,
                is_open: form.sub_option_1,
            }
        })
            .then(response => {
                isFetching.value = false;
                const data = response.data.data || response.data;
                if (data && (response.data.success !== false)) {
                    if (data.reg_no) form.from_app_no = data.reg_no;
                    if (data.security_code) form.from_security_code = data.security_code;
                    if (data.marla_display_size) form.from_size = data.marla_display_size;
                    if (data.member_name) form.client_name = data.member_name;
                    if (data.client_cnic) form.client_cnic = data.client_cnic;
                    if (data.plot_type_title) form.app_type = data.plot_type_title;
                    if (data.payment_plan_plot_price) form.payment_plan_plot_price = data.payment_plan_plot_price;
                    if (data.payment_plan_id) form.payment_plan_live_id = data.payment_plan_id;
                    if (data.payment_plan_down_payment) form.payment_plan_down_payment = data.payment_plan_down_payment;
                    if (data.legder_down_payment) form.ledger_down_payment = data.legder_down_payment;
                    if (data.legder_plot_price) form.ledger_plot_price = data.legder_plot_price;
                    if (data.sum_payment) form.sum_payment = data.sum_payment;
                    if (data.received_downpayment) form.received_downpayment = data.received_downpayment;
                } else {
                    alert(response.data?.message || 'Data not found. Please check the Reg No.');
                }
            })
            .catch(error => {
                isFetching.value = false;
                console.error("Error fetching data:", error);
                alert("Failed to fetch data.");
            });
    };

    const parseMarla = (sizeStr) => {
        if (!sizeStr) return 0;
        let str = String(sizeStr).toLowerCase().trim();
        let num = parseFloat(str);
        if (isNaN(num)) return 0;
        if (str.includes('kanal')) {
            return num * 20;
        }
        return num;
    };

    const fetchMergeToData = (index) => {
        const detail = form.merge_to_details[index];
        if (!detail.merge_to) return;
        isFetchingMergeTo.value[index] = true;

        axios.get(route('mergers.fetch-merge-to-data'), {
            params: {
                reg_no: detail.merge_to,
            }
        })
            .then(response => {
                isFetchingMergeTo.value[index] = false;

                const data = response.data.data || response.data;
                if (data && response.data.success !== false) {
                    if (data.is_block !== undefined && String(data.is_block) !== "0") {
                        alert(data.block_comments || "This item is blocked and cannot be used for merging.");
                        detail.merge_to = '';
                        return;
                    }

                    const fromSizeStr = form.from_size || '';
                    const toSizeStr = data.marla_display_size || '';

                    const fromSizeNum = parseMarla(fromSizeStr);
                    const toSizeNum = parseMarla(toSizeStr);

                    if (fromSizeNum > 0 && toSizeNum > 0 && toSizeNum > fromSizeNum) {
                        alert(`Merge To size (${toSizeStr}) cannot be greater than From size (${fromSizeStr}).`);
                        detail.merge_to = '';
                        return;
                    }

                    if (data.reg_no) detail.merge_to_no = data.reg_no;
                    if (data.security_code) detail.to_security_code = data.security_code;
                    if (data.marla_display_size) {
                        detail.to_size = data.marla_display_size;
                        if (toSizeNum > 0) {
                            detail.merging_fee = toSizeNum * 1000;
                        }
                    }
                    if (data.plot_type_title) detail.merge_app_type = data.plot_type_title;
                    if (data.payment_plan_plot_price_gen) detail.to_payment_plan_plot_price = data.payment_plan_plot_price_gen;
                    if (data.payment_plan_id) detail.to_payment_plan_live_id = data.payment_plan_id;
                    if (data.payment_plan_down_payment_gen) detail.to_payment_plan_down_payment = data.payment_plan_down_payment_gen;
                    if (data.payment_plan_down_payment_gen) detail.ledger_amount = data.payment_plan_down_payment_gen - detail.merging_fee;
                } else {
                    alert(response.data?.message || 'Data not found. Please check the Reg No.');
                }
            })
            .catch(error => {
                isFetchingMergeTo.value[index] = false;
                console.error("Error fetching data:", error);
                alert("Failed to fetch data.");
            });
    };

    const onSearchFromKeydown = (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchFromAppData();
        }
    };

    const onSearchToKeydown = (e, index) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchMergeToData(index);
        }
    };

    const addMergeToDetail = () => {
        form.merge_to_details.push({
            merge_to: '',
            merge_to_no: '',
            to_security_code: '',
            to_size: '',
            merge_app_type: '',
            ledger_amount: '',
            merging_fee: '',
            to_payment_plan_plot_price: '',
            to_payment_plan_live_id: '',
            to_payment_plan_down_payment: '',
        });
    };

    const removeMergeToDetail = (index) => {
        if (form.merge_to_details.length > 1) {
            form.merge_to_details.splice(index, 1);
        }
    };

    const formatCnic = (e, field) => {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
        form[field] = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
    };

    const submit = () => {
        form.post(route('mergers.store'), {
            preserveScroll: true,
        });
    };

    return {
        form,
        isFetching,
        isFetchingMergeTo,
        showOneExtraDropdown,
        showTwoExtraDropdowns,
        fileTypeOptions,
        mergingTypeOptions,
        fetchFromAppData,
        onSearchFromKeydown,
        onSearchToKeydown,
        addMergeToDetail,
        removeMergeToDetail,
        formatCnic,
        submit
    };
}
