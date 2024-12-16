<script setup>
import { ref, onMounted } from "vue";
import { read, utils, writeFileXLSX } from 'xlsx';

const rows = ref([]);

onMounted(async() => {
  /* Download from https://docs.sheetjs.com/pres.numbers */
  const f = await fetch("https://docs.sheetjs.com/pres.numbers");
  const ab = await f.arrayBuffer();

  /* parse workbook */
  const wb = read(ab);

  /* update data */
  rows.value = utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
});

/* get state data and export to XLSX */
function exportFile() {
  const ws = utils.json_to_sheet(rows.value);
  const wb = utils.book_new();
  utils.book_append_sheet(wb, ws, "Data");
  writeFileXLSX(wb, "SheetJSVueAoO.xlsx");
}
</script>

<template>
    <div class="card m-3">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Index</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="(row, idx) in rows" :key="idx">
                <td>{{ row.Name }}</td>
                <td>{{ row.Index }}</td>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary btn-sm" @click="exportFile">Export XLSX</button>
        </div>
    </div>
</template>
