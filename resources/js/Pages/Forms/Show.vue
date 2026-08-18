<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    form: Object,
});

function formatCurrency(value) {
    if (!value) return '-';
    return new Intl.NumberFormat('en-PK', {
        maximumFractionDigits: 0,
    }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

const printPage = () => {
    window.print();
};
</script>

<template>
    <Head title="Form Receipt" />
    
    <div class="receipt-container">
        <div class="top-bar hide-on-print">
            <Link :href="route('forms.index')">&#8592; Back</Link>
            <button @click="printPage" class="print-btn">Print</button>
        </div>

        <!-- ===================== CUSTOMER COPY ===================== -->
        <div class="receipt">
            <div class="receipt-title">
                <h1>Form Submission Receipt</h1>
                <div class="copy-label">(Customer Copy)</div>
            </div>

            <div class="section-header">
                <div>OS</div>
                <div class="center">Application Details</div>
                <div>Tracking Id: {{ form.tracking_code ?? '-' }}</div>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label" style="width:12%;">Form No:</td>
                    <td style="width:33%;">{{ form.form_no ?? '-' }}</td>
                    <td class="label col-divider" style="width:10%;">Name:</td>
                    <td>{{ form.client_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Reg No:</td>
                    <td>{{ form.qr_code ?? form.deposite_slip_no ?? '-' }}</td>
                    <td class="label col-divider">CNIC:</td>
                    <td>{{ form.client_cnic ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">App Size:</td>
                    <td>{{ form.app_type?.name ?? '' }}{{ form.app_type && form.size ? '-' : '' }}{{ form.size ?? '-' }}</td>
                    <td class="label col-divider">Contact:</td>
                    <td>{{ form.contact ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Down Payment:</td>
                    <td>{{ formatCurrency(form.down_payment) }}</td>
                    <td class="label col-divider">Address:</td>
                    <td>{{ form.address ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Reg Price:</td>
                    <td>{{ formatCurrency(form.plot_price) }}</td>
                    <td class="col-divider"></td>
                    <td></td>
                </tr>
            </table>

            <div class="disclaimer">
                This Receipt is subject to confirmation of Payment clearance from Bank/Accounts Dept. Management reserve the right to accept/reject the registration application.
            </div>

            <div class="submit-row">
                <div><span>Submitted By:</span><span class="underline-blank">{{ form.submitted_by ?? '' }}</span></div>
                <div><span>Dealer:</span><span class="underline-blank">{{ form.dealer_id ?? '' }}</span></div>
                <div><span>Dealer Cnic:</span><span class="underline-blank"></span></div>
                <div><span>Submitter Cnic:</span><span class="underline-blank">{{ form.submitter_cnic ?? '' }}</span></div>
            </div>

            <div class="officer-box">
                <div class="officer-row">
                    <div><span class="label">Officer Name:</span> <u>{{ form.user?.name ?? 'Admin' }}</u></div>
                    <div><span class="label">DownPayment:</span> <u>{{ formatCurrency(form.down_payment) }}</u></div>
                    <div><span class="label">Office:</span> <u>{{ form.office?.name ?? 'Head Office' }}</u></div>
                </div>
                <div class="officer-row">
                    <div><span class="label">Date:</span> <u>{{ formatDate(form.created_at) }}</u></div>
                    <div><span class="label">Reg Price:</span> <u>{{ formatCurrency(form.plot_price) }}</u></div>
                    <div><span class="label">Signature:</span><span class="underline-blank"></span></div>
                </div>
            </div>
        </div>

        <!-- ===================== OFFICE COPY ===================== -->
        <div class="receipt">
            <div class="receipt-title">
                <h1>Form Submission Receipt</h1>
                <div class="copy-label">(Office Copy)</div>
            </div>

            <div class="section-header">
                <div>OS</div>
                <div class="center">Application Details</div>
                <div>Tracking Id: {{ form.tracking_code ?? '-' }}</div>
            </div>

            <table class="details-table">
                <tr>
                    <td class="label" style="width:12%;">Form No:</td>
                    <td style="width:33%;">{{ form.form_no ?? '-' }}</td>
                    <td class="label col-divider" style="width:10%;">Name:</td>
                    <td>{{ form.client_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Reg No:</td>
                    <td>{{ form.qr_code ?? form.deposite_slip_no ?? '-' }}</td>
                    <td class="label col-divider">CNIC:</td>
                    <td>{{ form.client_cnic ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">App Size:</td>
                    <td>{{ form.app_type?.name ?? '' }}{{ form.app_type && form.size ? '-' : '' }}{{ form.size ?? '-' }}</td>
                    <td class="label col-divider">Contact:</td>
                    <td>{{ form.contact ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Down Payment:</td>
                    <td>{{ formatCurrency(form.down_payment) }}</td>
                    <td class="label col-divider">Address:</td>
                    <td>{{ form.address ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Reg Price:</td>
                    <td>{{ formatCurrency(form.plot_price) }}</td>
                    <td class="col-divider"></td>
                    <td></td>
                </tr>
            </table>

            <div class="disclaimer">
                This Receipt is subject to confirmation of Payment clearance from Bank/Accounts Dept. Management reserve the right to accept/reject the registration application.
            </div>

            <div class="submit-row">
                <div><span>Submitted By:</span><span class="underline-blank">{{ form.submitted_by ?? '' }}</span></div>
                <div><span>Dealer:</span><span class="underline-blank">{{ form.dealer_id ?? '' }}</span></div>
                <div><span>Dealer Cnic:</span><span class="underline-blank"></span></div>
                <div><span>Submitter Cnic:</span><span class="underline-blank">{{ form.submitter_cnic ?? '' }}</span></div>
            </div>

            <div class="officer-box">
                <div class="officer-row">
                    <div><span class="label">Officer Name:</span> <u>{{ form.user?.name ?? 'Admin' }}</u></div>
                    <div><span class="label">DownPayment:</span> <u>{{ formatCurrency(form.down_payment) }}</u></div>
                    <div><span class="label">Office:</span> <u>{{ form.office?.name ?? 'Head Office' }}</u></div>
                </div>
                <div class="officer-row">
                    <div><span class="label">Date:</span> <u>{{ formatDate(form.created_at) }}</u></div>
                    <div><span class="label">Reg Price:</span> <u>{{ formatCurrency(form.plot_price) }}</u></div>
                    <div><span class="label">Signature:</span><span class="underline-blank"></span></div>
                </div>
            </div>
        </div>

        <hr class="footer-line">
    </div>
</template>

<style scoped>
.receipt-container {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13px;
  color: #000;
  background: #fff;
  margin: 20px auto;
  padding: 20px 30px;
  min-height: 100vh;
  max-width: 800px;
  box-shadow: 0 0 15px rgba(0,0,0,0.05);
}
.top-bar {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px dashed #000;
  padding-bottom: 8px;
  margin-bottom: 10px;
}
.top-bar a, .print-btn {
  color: #1a56db;
  text-decoration: none;
  font-size: 14px;
  font-weight: bold;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}
.print-btn:hover, .top-bar a:hover {
  text-decoration: underline;
}
.receipt {
  margin-bottom: 25px;
}
.receipt-title {
  text-align: center;
  position: relative;
  margin: 10px 0 4px 0;
}
.receipt-title h1 {
  font-size: 22px;
  font-weight: normal;
  margin: 0;
  display: inline-block;
}
.copy-label {
  position: relative;
  float: right;
  top: -28px;
  font-weight: bold;
  font-size: 13px;
}
.section-header {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px dashed #000;
  padding-bottom: 4px;
  margin-bottom: 4px;
  font-weight: normal;
}
.section-header .center {
  flex: 1;
  text-align: center;
}
.details-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #000;
}
.details-table td {
  border: none;
  padding: 4px 8px;
  vertical-align: top;
  font-size: 13px;
}
.details-table tr {
  border-bottom: 1px dotted #000;
}
.details-table tr:last-child {
  border-bottom: none;
}
.label {
  font-weight: bold;
  white-space: nowrap;
}
.col-divider {
  border-left: 1px solid #000;
}
.disclaimer {
  font-size: 12px;
  margin: 6px 0 14px 0;
}
.submit-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 14px;
  font-size: 13px;
}
.submit-row span {
  font-weight: bold;
}
.underline-blank {
  border-bottom: 1px solid #000;
  display: inline-block;
  min-width: 140px;
  margin-left: 4px;
  text-align: center;
  font-weight: normal !important;
}
.officer-box {
  border: 1px solid #000;
  padding: 12px;
}
.officer-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 24px;
  font-size: 13px;
}
.officer-row:last-child {
  margin-bottom: 4px;
}
.officer-row .label {
  font-weight: bold;
}
.officer-row u {
  font-weight: normal;
  text-decoration: underline;
}
hr.footer-line {
  border: none;
  border-top: 1px solid #000;
  margin-top: 30px;
}

@media print {
  .hide-on-print {
    display: none !important;
  }
  body {
    background: #fff;
  }
  .receipt-container {
    padding: 0;
    margin: 0;
    min-height: auto;
  }
}
</style>
