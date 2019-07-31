<template>
<div>
  <md-table v-model="searched" md-sort="name" md-sort-order="asc" md-card md-fixed-header>
    <md-table-toolbar>
      <div class="md-toolbar-section-start">
        <h1 class="md-title">Orders</h1>
      </div>

      <md-field md-clearable class="md-toolbar-section-end">
        <md-input placeholder="Search by order title..." v-model="search" @input="searchOnTable" />
      </md-field>
    </md-table-toolbar>

    <md-table-empty-state
      md-label="No orders found"
      :md-description="`No order found for this '${search}' query. Try a different search term.`">
    </md-table-empty-state>

    <md-table-row slot="md-table-row" slot-scope="{ item }">
      <md-table-cell md-label="Order Id" md-sort-by="id" md-numeric>{{ item.order_id }}</md-table-cell>
      <md-table-cell md-label="Order Title" md-sort-by="o_title">{{ item.o_title }}</md-table-cell>
      <md-table-cell md-label="Order Description" md-sort-by="o_description">{{ item.o_description }}</md-table-cell>
      <md-table-cell md-label="Order Status" md-sort-by="o_status">{{ item.o_status }}</md-table-cell>
    </md-table-row>
  </md-table>
</div>
</template>

<script>
const toLower = text => {
  return text.toString().toLowerCase()
}

const searchByName = (items, term) => {
  if (term) {
    return items.filter(item => toLower(item.o_title).includes(toLower(term)))
  }

  return items
}

export default {
  name: 'orderList',
  props:['orders'],
  data: () => ({
    search: null,
    searched: [],
  }),
  methods: {
    searchOnTable() {
      this.searched = searchByName(this.orders, this.search)
    }
  },
  created() {
    this.searched = this.orders
  }
}
</script>

<style lang="scss" scoped>
.md-field {
    max-width: 300px;
}
</style>
