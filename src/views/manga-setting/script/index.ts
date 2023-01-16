import {Delete, Edit} from '@element-plus/icons-vue'
import {defineComponent} from 'vue'
import {ElMessageBox} from 'element-plus'
import {get_manga, update_manga, delete_manga} from "@/api/manga";
import tablePager from "@/components/table-pager.vue";

export default defineComponent({
    name: 'index',
    setup() {
        return {
            Edit,
            Delete,
        }
    },
    // 数据
    data() {
        return {
            count: 0,
            tableData: [],
            editMangaDialog: false,
            form: {
                mangaId: '',
                mangaName: '',
                mangaPath: '',
                mangaCover: '',
                browseType: '',
            },
            formInit: {
                mangaId: '',
                mangaName: '',
                mangaPath: '',
                mangaCover: '',
                browseType: 'flow',
            },
        }
    },

    // 传值
    props: [],

    // 计算
    computed: {},

    // 组件
    components: {tablePager},

    // 方法
    methods: {
        /***
         * 关闭弹窗
         */
        dialog_close() {
            this.editMangaDialog = false;
        },
        /**
         * 开启弹窗
         */
        dialog_open() {
            Object.assign(this.form, this.formInit);

            this.editMangaDialog = true;

        },

        /**
         * 加载表格数据
         */
        async load_table(page = 1, pageSize = 10) {
            const start = (page - 1) * pageSize;
            const res = await get_manga(0, start, pageSize);
            this.count = Number(res.data.count);
            this.tableData = res.data.list;
        },
        /**
         * 重载数据 页码不变
         */
        reload_table(){
            (this.$refs as any).pager.reload_page();
        },
        /**
         * 编辑漫画
         * @param index
         * @param row
         */
        edit_manga(index: number, row: any) {
            this.dialog_open();
            Object.assign(this.form, row);
        },

        /**
         * 执行修改请求
         */
        async update_manga() {
            const res = await update_manga(this.form);
            if (res.data.code === 0) {
                this.editMangaDialog = false;
                this.reload_table();
            }
        },

        /**
         * 删除漫画
         * */
        async delete_manga(index: number, row: any) {
            ElMessageBox.confirm('确认删除此漫画?', '确认删除', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(async () => {
                const res = await delete_manga(row.mangaId);

                if (res.data.code === 0) {
                    this.reload_table();
                }
            }).catch(() => {
            })
        },
    },

    // 生命周期
    created() {
        this.load_table();
    },
})