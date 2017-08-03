<template>
	<section>
        <!-- filters -->
		<el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
			<el-form :inline="true" :model="filters">
				<el-form-item>
					<el-input v-model="filters.name" placeholder="name"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" v-on:click="getData" icon="search">Filter</el-button>
				</el-form-item>
				<el-form-item>
                    <el-button type="primary" @click="handleAdd" icon="plus">Add</el-button>
				</el-form-item>
			</el-form>
		</el-col>

		<!-- table -->
		<el-table :data="persons" highlight-current-row v-loading="listLoading" element-loading-text="Loading ..." @sort-change="handleSortChange" @filter-change="handleFilterChange" style="width: 100%;">
			<el-table-column prop="id" width="60" sortable=false> </el-table-column>
            <el-table-column prop="name" label="Name" width="220" sortable=false> </el-table-column>
			<el-table-column prop="cpf" label="CPF" width="200" sortable=false> </el-table-column>
			<el-table-column prop="rg" label="RG" width="200" v-if="isSC()" sortable=false></el-table-column>
			<el-table-column prop="birth" label="Birth" width="120" sortable=false> </el-table-column>
			<el-table-column label="Actions" width="150">
				<template scope="scope">
					<el-button size="small" @click="handleEdit(scope.$index, scope.row)" icon="edit"></el-button>
					<el-button type="danger" size="small" @click="handleDel(scope.$index, scope.row)" icon="delete"></el-button>
				</template>
			</el-table-column>
		</el-table>

		<!-- pagination -->
		<el-col :span="24" class="toolbar">
			<el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="20" :total="total" style="float:right;">
			</el-pagination>
		</el-col>

		<!-- add dialog -->
		<el-dialog title="New person" v-model="addFormVisible" :close-on-click-modal="false">
			<el-form :model="addForm" label-width="80px" ref="addForm">
				<el-form-item label="Name" prop="name" :error="errors.get('name')">
					<el-input v-model="addForm.name" @change="errors.clear('name')" icon="fa fa-vcard"></el-input>
				</el-form-item>
				<el-form-item label="CPF" prop="cpf" :error="errors.get('cpf')">
					<el-input v-model="addForm.cpf"  @change="errors.clear('cpf')" icon="fa fa-user"></el-input>
				</el-form-item>
				<el-form-item label="RG" prop="rg" :error="errors.get('rg')" v-if="isSC()">
					<el-input v-model="addForm.rg"  @change="errors.clear('rg')" icon="fa fa-user-o"></el-input>
				</el-form-item>
				<el-form-item label="Birth" :error="errors.get('birth')">
					<el-date-picker type="date" name="birth" placeholder="Select" v-model="addForm.birth" @change="errors.clear('birth')"></el-date-picker>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click.native="addFormVisible = false">Cancel</el-button>
				<el-button type="primary" @click.native="addSubmit" :loading="addLoading">Create</el-button>
			</div>
		</el-dialog>

		<!-- edit dialog -->
        <el-dialog title="Edit person" v-model="editFormVisible" :close-on-click-modal="false">
			<el-form :model="editForm" label-width="80px" ref="editForm">
				<el-form-item label="Name" prop="name" :error="errors.get('name')">
					<el-input v-model="editForm.name" @change="errors.clear('name')" icon="fa fa-vcard"></el-input>
				</el-form-item>
				<el-form-item label="CPF" prop="cpf" :error="errors.get('cpf')">
					<el-input v-model="editForm.cpf"  @change="errors.clear('cpf')" icon="fa fa-user"></el-input>
				</el-form-item>
				<el-form-item label="RG" prop="rg" :error="errors.get('rg')" v-if="isSC()">
					<el-input v-model="editForm.rg"  @change="errors.clear('rg')" icon="fa fa-user-o"></el-input>
				</el-form-item>
				<el-form-item label="Birth" :error="errors.get('birth')">
					<el-date-picker type="date" name="birth" placeholder="Select" v-model="editForm.birth" @change="errors.clear('birth')"></el-date-picker>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click.native="editFormVisible = false">Cancel</el-button>
				<el-button type="primary" @click.native="editSubmit" :loading="editLoading">Edit</el-button>
			</div>
		</el-dialog>

	</section>
</template>

<script>
	import util from '../common/js/util'
	import Errors from '../common/js/Errors';

	import { getPersonListPage, addPerson, editPerson, removePerson } from '../endpoints';

	export default {
		data() {
			return {
                sortBy: 'id,asc',
				filters: {
					name: '',
					created_at: '',
					birth: ''
				},
				persons: [],
                errors: new Errors(),
				total: 0,
				page: 1,
				listLoading: false,
				addFormVisible: false,
				addLoading: false,
				editFormVisible: false,
				editLoading: false,
				addForm: {},
                editForm: {}
			}
		},
		methods: {
			isSC(){
				return window.state == "SC";
			},
			handleSortChange(val) {
                if (val.prop != null) {
                    var sort = val.order.startsWith('a') ? 'asc' : 'desc';
                    this.sortBy = val.prop + ',' + sort;
                    this.getData();
                }
			},
				handleFilterChange(val) {
			    
				this.getData();
			},
			handleCurrentChange(val) {
				this.page = val;
				this.getData();
			},
			getData() {
				let para = {
					page: this.page,
					name: this.filters.name,
					birth: this.filters.birth,
					created_at: this.filters.created_at,
					sortBy: this.sortBy,
				};
				this.listLoading = true;
				getPersonListPage(para).then((res) => {
					this.total = res.data.total;
					this.persons = res.data.data;
					this.listLoading = false;
				});
			},
			handleDel: function (index, row) {
                this.$confirm('This will permanently delete the person. Continue?', 'Warning', {
					type: 'warning'
				}).then(() => {
					this.listLoading = true;
					let params = { id: row.id };
					removePerson(params).then((response) => {
						this.listLoading = false;
                        this.$notify({
                            title: 'Success',
                            message: response.data.message,
                            type: 'success'
                        });
						this.getData();
					});
				}).catch(() => {
				});
			},
			handleEdit: function (index, row) {
				this.editFormVisible = true;
				this.editForm = Object.assign({}, row);
                this.errors = new Errors()
			},
			handleAdd: function () {
				this.addFormVisible = true;
				this.addForm = {
					name: '',
					cpf: '',
					rg: '',
					birth: ''
				};
                this.errors = new Errors()
			},
			addSubmit: function () {
				this.addLoading = true;
                addPerson(this.addForm).then((response) => {
                    this.addLoading = false;
                    this.$notify({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });
                    this.$refs['addForm'].resetFields(); // maybe not necessary
                    this.addFormVisible = false;
                    this.getData();
                }).catch((error) => {
                    this.errors.record(error.response.data);
                    this.addLoading = false;
                });
            },
			editSubmit: function () {
                editPerson(this.editForm).then((response) => {
                    this.editLoading = false;
                    this.$notify({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });
                    this.$refs['editForm'].resetFields();
                    this.editFormVisible = false;
                    this.getData();
                }).catch((error) => {
                    this.errors.record(error.response.data);
                    this.editLoading = false;
                });
			},
        },
		mounted() {
			this.getData();
		}
	}
</script>

<style scoped>
</style>
