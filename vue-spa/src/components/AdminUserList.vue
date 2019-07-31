
<template>
<div class="adminUserList">
  <md-table v-model="searched" md-sort="name" md-sort-order="asc" md-card md-fixed-header>
    <md-table-toolbar>
      <div class="md-toolbar-section-start">
        <h1 class="md-title">Users</h1>
      </div>

      <md-field md-clearable class="md-toolbar-section-end">
        <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
      </md-field>
    </md-table-toolbar>

    <md-table-empty-state
      md-label="No user found"
      :md-description="`No order found for this '${search}' query. Try a different search term.`">
    </md-table-empty-state>

    <md-table-row slot="md-table-row" slot-scope="{ item }">
      <md-table-cell md-label="User Id" md-sort-by="id" md-numeric>{{ item.id }}</md-table-cell>
      <md-table-cell md-label="User Name" md-sort-by="name" md-numeric>{{ item.name }}</md-table-cell>
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
    return items.filter(item => toLower(item.name).includes(toLower(term)))
  }

  return items
}

export default {
  name: 'adminUserList',
  props:['users'],
  data: () => ({
    search: null,
    searched: [],
  }),
  methods: {
    searchOnTable() {
      this.searched = searchByName(this.users, this.search)
    }
  },
  created() {
    this.searched = this.users
  }
}
</script>

<style lang="scss" scoped>
  .adminUserList{
    max-width: 600px;
    margin: 0 auto;
  }

  .md-table-head-container md-ripple {
    text-align:left
  }

  .md-table-cell-container {
    text-align:left
  }
</style>
