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
      <md-checkbox v-model="byUser">Only search by user id..</md-checkbox>
    </md-table-toolbar>

    <md-table-empty-state
      md-label="No orders found"
      :md-description="`No order found for this '${search}' query. Try a different search term.`">
    </md-table-empty-state>

    <md-table-row slot="md-table-row" slot-scope="{ item }">
      <md-table-cell md-label="Order Id" md-sort-by="order_id" md-numeric>{{ item.order_id }}</md-table-cell>
      <md-table-cell md-label="User Id" md-sort-by="user_id" md-numeric>{{ item.user_id }}</md-table-cell>
      <md-table-cell md-label="Order Title" md-sort-by="o_title">{{ item.o_title }}</md-table-cell>
      <md-table-cell md-label="Order Description" md-sort-by="o_description">{{ item.o_description }}</md-table-cell>
      <md-table-cell md-label="Order Status" md-sort-by="o_status">
        <md-button class="md-raised md-primary" @click="displayDialog(item.o_status,item.order_id)">{{item.o_status}}</md-button>
      </md-table-cell>
    </md-table-row>
  </md-table>

  <md-dialog :md-active.sync="showDialog">
    <md-dialog-title>Update order {{tempOrderId}} status</md-dialog-title>
    <div class="selector-wrapper">
    <md-field>
      <md-select v-model="tempOrderStatus" name="status" id="status" md-dense>
        <md-option value="pending">Pending</md-option>
        <md-option value="await payment">Await payment</md-option>
        <md-option value="paid">Paid</md-option>
      </md-select>
    </md-field>
    </div>
    <md-dialog-actions>
      <md-button class="md-primary" @click="showDialog = false">Close</md-button>
      <md-button class="md-primary" @click="updateStatus">Save</md-button>
    </md-dialog-actions>
  </md-dialog>
</div>
</template>

<script>
const toLower = text => {
  return text.toString().toLowerCase()
}

const searchTable = (items, term, byuser) => {
  if (term && !byuser) {
    return items.filter(item => toLower(item.o_title).includes(toLower(term)))
  } else if(term && byuser){
    return items.filter(item => toLower(item.user_id).includes(toLower(term)))
  }
  return items
}

export default {
  name: 'adminOrderList',
  props:['orders'],
  data: () => ({
    search: null,
    searched: [],
    showDialog:false,
    tempOrderStatus:null,
    tempOrderId:null,
    byUser:false
  }),
  methods: {
    searchOnTable() {
      this.searched = searchTable(this.orders, this.search,this.byUser)
    },
    displayDialog(status,order_id) {
      this.showDialog = true
      this.tempOrderStatus = status
      this.tempOrderId = order_id
    },
    updateStatus() {
      var order_id = this.tempOrderId;
      var token = localStorage.getItem("admintoken")
      var self = this

      this.axios
        .post(this.$store.state.apiUrl+"/updateOrderStatus/"+order_id,{
          status: this.tempOrderStatus,
        },{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response) {
          if(response.data.status == "success") {
            self.showDialog = false
            self.$emit('updateStatus');
            self.$toasted.global.app_success({
              message : response.data.msg
            });
          } else{
            self.showDialog = true
            self.$toasted.global.app_error({
              message : response.data.msg
            });
          }
        })
        .catch(function (error) {
          self.userSaved = false
          self.sending = false
          self.$toasted.global.app_error({
            message : "Server Error: "+error
          });
        });
    },
  },
  mounted() {
    this.searched = this.orders.slice();
  },
}
</script>

<style lang="scss" scoped>
.md-field {
    max-width: 300px;
}

.selector-wrapper {
  padding:0px 20px
}
</style>
