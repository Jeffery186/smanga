export default {
    sidebar: {
        mediaList: '媒體庫',
        mangaList: '漫畫列表',
        chapterList: '章節列表',
        history: '歷史記錄',
        bookmark: '書簽',
        account: '賬戶管理',
        mediaManage: '媒體庫管理',
        mangaManage: '漫畫管理',
        pathManage: '路徑管理',
        chapterManage: '章節管理',
        bookmarkManage: '書簽管理',
        compressManage: '轉換管理',
        wiki: '使用說明',
    },
    option: {
        option: '操作',
        add: '新增',
        delete: '刪除',
        modify: '修改',
        remove: '移除',
        confirm: '確定',
        cancel: '取消',
        removeFirst: '移除首頁',
        recoveryFirst: '恢復首頁',
        direction: '切換閱讀方向',
        ltr: '左 -> 右',
        rtl: '右 -> 左',
    },
    account: {
        add: '新增用戶',
        modify: '修改用戶',
        serial: '序號',
        id: '用戶id',
        name: '用戶名',
        pass: '密碼',
        registerTime: '註冊日期',
        option: '操作',
        nameLabel: '用戶名:',
        passLabel: '密碼:',
        namePlace: '請輸入用戶名',
        passPlace: '請輸入新密碼',
        passModifyPlace: '請輸入新密碼(留空則不修改)',
        confirmBoxTitle: '確認刪除',
        confirmBoxText: '確認刪除此用戶?',
        formWarning: '用戶名長3-20位,以字母開頭',
        before: '上一章',
        next: '下一章',
        note:{
            name:'"用戶名": 長3-20位,以字母開頭',
            pass:'"密碼": 留空為不修改',
        },
    },
    mediaManage: {
        add: '新增媒體庫',
        modify: '修改媒體庫',
        id: '媒體庫id',
        name: '媒體庫名稱',
        createTime: '創建時間',
        path: '路徑',
        form: {
            name: '媒體庫名稱:',
            type: '媒體庫類型:',
            file: '文件類型:',
            browse: '默認瀏覽方式:',
            directory: '文件夾結構:',
            removeFirst: '剔除首頁:',
            direction: '翻頁方向:',
        },
        note: {
            name: '"媒體庫名稱": 必填,不能為空',
            type: '"媒體庫類型": 會影響smanga對媒體庫路徑的掃描方式.目前分為普通漫畫與單本漫畫,單本漫畫對於普通漫畫會少一個"章節"的目錄層級.',
            browse: '"默認瀏覽方式": 選擇此媒體庫裏漫畫的默認瀏覽方式.您還可以通過瀏覽界面的頂部功能菜單調整,但我們還是建議將不同類型的漫畫放在不同的媒體庫,以便於進行管理.',
            directory: '"文件夾結構": 此選項支持為路徑多加一層目錄,例如您希望通過年份來管理漫畫 "example/2022/manga/chapter/..."',
            removeFirst: '"剔除首頁": 在"雙頁模式"下,通過在閱讀中暫時剔除首頁,可對其偶數頁碼,使漫畫左右兩頁合成一張整圖,請熟悉漫畫頁碼後更改此項.您也可以在瀏覽界面通過功能菜單來臨時更改此項.',
            direction: '"翻頁方向": 在"雙頁模式"下,更改第一與第二張圖的展示順序,使得漫畫左右兩頁合成一張整圖.您也可以在瀏覽界面通過功能菜單來臨時更改此項.',
        },
        place: {
            name: '請輸入媒體庫名稱',
            browse: '請選擇默認瀏覽方式',
        },
        select: {
            mediaType0: '漫畫(漫畫 -> 章節 -> 圖片)',
            mediaType1: '單本(漫畫 -> 圖片)',
            fileType0: '圖片',
            browse0: '瀑布',
            browse1: '單頁',
            browse2: '雙頁',
            directory0: '漫畫 -> 章節(或壓縮包) -> 圖片',
            directory1: '目錄 -> 漫畫 -> 章節(或壓縮包) -> 圖片',
            ltr: '左到右',
            rtl: '右到左',
        },
        confirm: {
            title: '確認刪除',
            text: '確認刪除此媒體庫?',
        },
        title:{
            read:'閱讀選項',
        },
    },
    path: {
        id: '路徑id',
        add: '新增路徑',
        modify: '編輯路徑',
        path: '路徑',
        createTime: '創建時間',
        form: {
            add: '新增路徑:',
            path: '路徑:',
        },
        place: {
            add: '請輸入路徑',
        },
        button: {
            re: '重新掃描',
            update: '增量掃描',
        },
        warning: {
            name: '媒體庫名稱不能為空!',
        },
        confirm: {
            title: '確認刪除',
            text: '確認刪除此媒體庫?',
            title1: '',
            text1: '確認刪除此路徑? 與之關聯的漫畫和章節都會被清除!',
            title2: '確認重新掃描',
            text2: '確認刪除此路徑? 將清除與之相關的漫畫與章節並重新掃描添加!',
        }
    },
    mangaManage: {
        id: '媒體庫id',
        name: '漫畫名稱',
        createTime: '創建時間',
        updateTime: '更新時間',
        modify: '編輯漫畫',
        form: {
            name: '漫畫名稱:',
            browse: '瀏覽方式:',
            path: '漫畫路徑:',
            poster: '漫畫封面:',
        },
        place: {
            name: '請輸入媒體庫名稱',
            browse: '請選擇瀏覽方式',
            path: '請輸入路徑',
            poster: '請輸入漫畫封面路徑',
        },
        confirm: {
            title: '確認刪除',
            text: '確認刪除此漫畫?',
            title1: '確認移除',
            text1: '確認移除此漫畫?',
            title2: '確認移除',
            text2: '確認移除此漫畫並刪除源文件?',
        },
        warning: {
            name: '漫畫名不能為空!',
            path: '漫畫路徑不能為空!'
        },

    },
    chapterManage: {
        id: '章節id',
        name: '章節名稱',
        createTime: '創建時間',
        updateTime: '更新時間',
        modify: '編輯章節',
        form: {
            name: '章節名稱:',
            path: '章節路徑:',
            poster: '章節封面:',
        },
        place: {
            name: '請輸入章節名稱',
            path: '請輸入章節路徑',
            poster: '請輸入章節封面路徑'
        },
        warning: {
            name: '章節名稱不能為空!',
            path: '章節路徑不能為空!',
        },
        confirm: {
            title: '確認刪除',
            text: '確認刪除此章節?',
            title1: '確認移除',
            text1: '確認移除此章節?',
            title2: '確認移除',
            text2: '確認移除此章節並刪除源文件?',
        },
    },
    bookmarkManage: {
        add: '添加書簽',
        remove: '移除書簽',
        id: "書簽id",
        page: '頁碼',
        createTime: '添加日期',
        confirm: {
            title: '確認刪除',
            text: '確認刪除此書簽?',
        },
    },
    compressManage: {
        id: '轉換id',
        type: '轉換類型',
        source: '源路徑',
        path: '轉換路徑',
        num: '圖片總數',
        createTime: '轉換日期',
        confirm: {
            title: '確認刪除',
            text: '確認刪除此轉換記錄?',
        },
    },
    browse: {
        flow: '條漫模式',
        single: '單頁模式',
        double: '雙頁模式',
    },
    page: {
        before: '上一章',
        next: '下一章',
        lastPage: '已經位於首頁',
        firstPage: '已經位於尾頁',
        lastChapter: '已經到了最後一章',
        firstChapter: '已經位於第一章',
    },
    theme: {
        grey: '灰色',
        white: '白色',
        black: '黑色',
        red: '紅色',
        redLight: '淺紅',
        orange: '橘色',
        gold: '金色',
        yellow: '黃色',
        greenLight: '青檸',
        green: '綠色',
        cyan: '青色',
        blueLight: '藍色',
        blueDraw: '深藍',
        purple: '醬紫',
        magenta: '洋紅',
    },
    404: '404，您請求的文件不存在!',
}
