<template>
    <div class="container-fluid text-center">

        <h1 v-if="state === 'loading'">Betöltés...</h1>
        <h4 v-if="error" class="text-danger">{{ error }}</h4>
        <div v-if="state === 'loaded'">
            <p class="text-danger" v-if="invalidRows.length">Egy tárcában maximum 200 000 Ft lehet.</p>
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th v-for="month in months" :key="month.id">{{ month.name }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="wallet in wallets" :key="wallet.id"
                    :class="invalidRows.includes(wallet.id) ? 'bg-danger' : ''">
                    <th>{{ wallet.name }}</th>
                    <td v-for="month in months" :key="month.id">
                        <input v-model.number="amounts[wallet.id][month.id]" type="number" class="form-control" :class="amounts[wallet.id][month.id] < 0 ? 'is-invalid' : ''" min="0">
                    </td>
                </tr>
                </tbody>
            </table>
            <p :class="limitLeft < 0 ? 'text-danger' : ''"><strong>Még felhasználható összeg: {{ limitLeft }}
                Ft</strong></p>
            <button type="submit" class="btn btn-success" :disabled="invalidRows.length || limitLeft < 0"
                    @click.prevent="submit">
                Mentés
            </button>

            <a href="/api/wallets/export" class="btn btn-warning left-marg-10">
                Export
            </a>

            <p v-if="success" class="text-success">{{ success }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    components: {},
    data() {
        return {
            wallets: [],
            months: [],
            amounts: [],
            total: 0,
            error: '',
            state: 'loading',
            success: ''
        }
    },
    async mounted() {
        let vm = this;
        await vm.getData();
    },
    computed: {
        invalidRows: function () {
            let vm = this;
            let invalid = [];
            let total = 0;
            vm.wallets.forEach(wallet => {
                let sum = 0;
                Object.values(vm.amounts[wallet.id]).forEach(item => {
                    if (item) {
                        sum += item;
                        total += item;
                    }
                });
                if (sum > vm.wallet_limit) {
                    invalid.push(wallet.id);
                }
            });
            vm.total = total;
            return invalid;
        },
        limitLeft: function () {
            let vm = this;
            return vm.total_limit - vm.total;
        }
    },
    methods: {
        async getData() {
            let vm = this;
            axios.get('/api/wallets/edit')
                .then(function (response) {
                    let data = response.data;
                    if (data && data.wallets && data.months) {
                        vm.wallets = data.wallets;
                        vm.months = data.months;
                        vm.amounts = data.amounts;
                    }
                    vm.state = 'loaded';
                }).catch((e) => {
                vm.error = 'Error happened during request: ' + e;
                vm.state = 'error';
            });
        },
        submit() {
            let vm = this;
            let formData = new FormData();

            vm.wallets.forEach(wallet => {
                vm.months.forEach(month => {
                    formData.append('amounts[' + wallet.id + '][' + month.id + ']', vm.amounts[wallet.id][month.id]);
                });
            });
            let config = {
                header: {
                    'Content-Type': 'multipart/form-data'
                }
            }

            axios.post('/api/wallets/update', formData, config)
                .then(function (response) {
                    if (response.data.success) {
                        vm.error = '';
                        vm.success = 'Adatok frissítve';
                    }
                }).catch((e) => {
                    vm.error = e.response.data.message;
            });
        }
    },
    setup() {
        const total_limit = process.env.MIX_TOTAL_LIMIT;
        const wallet_limit = process.env.MIX_WALLET_LIMIT;

        return {
            total_limit,
            wallet_limit
        }
    }
};
</script>
<style scoped>
.left-marg-10 {
    margin-left:10px;
}
</style>
