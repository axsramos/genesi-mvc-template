use sbadmin_local;

-- (CasAppCod) é o APP_KEY localizado no arquivo '.env'
SET @CasAppCod = 'UFJPSkVDVCBTQkFETUlOIExPQ0FM';
SET @CasAppKey = '28bfa817-55b6-11f0-8e0d-fc4596f8a36d';

-- (CasAppDsc) é o APP_NAME localizado no arquivo '.env'
SET @CasAppDsc = 'Manager';
SET @CasAppObs = 'Painel Administrativo Manager';
SET @CasAppGrp = 'MANAGER';
SET @CasAppVer = 'V1.0.0';
SET @CasAppVerLnk = '';
SET @now = now();
SET @usr = 'query_support';

-- Repositótio principal do usuário admin.
SET @CasRpsCod = '60dc1348618d255fe9b9d81ea4219e48';
SET @CasRpsDsc = 'Principal (admin)';

-- Repositótio principal do usuário suporte.
SET @CasRpsCod_SA = 'f496066a1371151df6ba00068c197a3f';
SET @CasRpsDsc_SA = 'Principal (support)';

-- // ADMIN_MANAGER // --
-- user: manager-admin@uorak.com
-- (CasUsrCod) é o md5 do usuário manager-admin@uorak.com, pode ser obtido em 'App\Static\Auth\UserAccounts.json em (USR_ID)'
-- (CasRpsCod) é o md5 do usuário manager-admin@uorak.com, pode ser obtido em 'App\Static\Auth\UserAccounts.json em (Repository)'
SET @CasUsrCod = '60dc1348618d255fe9b9d81ea4219e48';
SET @CasUsrNme = 'ADMIN';
SET @CasUsrSnm = 'ACCOUNT';
SET @CasUsrDsc = 'ADMIN ACCOUNT';
SET @CasUsrDmn = '@uorak.com';
SET @CasUsrLgn = 'manager-admin';
SET @CasUsrPwd = 'a9aa976dbb0d38c94731431dc48a1aee';
SET @CasRpuDsc = 'Principal';
SET @CasTusCod = 'QURNSU5JU1RSQVRPUiBQT1JUQUw='; -- ADMINISTRATOR PORTAL (BASE64)
SET @CasTusDsc = 'ADMINISTRATOR PORTAL';
SET @CasTusLnk = '/Manager/Dashboard';
SET @CasTusGrp = 'ADMINISTRATOR MANAGER';

-- // SUPPORT ACCOUNT // --
-- user: manager-support@uorak.com
SET @CasUsrCod_SA = 'f496066a1371151df6ba00068c197a3f';
SET @CasUsrNme_SA = 'SUPPORT';
SET @CasUsrSnm_SA = 'ACCOUNT';
SET @CasUsrDsc_SA = 'SUPPORT ACCOUNT';
SET @CasUsrDmn_SA = '@uorak.com';
SET @CasUsrLgn_SA = 'manager-support';
SET @CasUsrPwd_SA = '7a8489429cff6d2bc3ac91ca8ac09ad0';
SET @CasRpuDsc_SA = 'Principal';
SET @CasTusCod_SA = 'U1VQUE9SVCBBQ0NPVU5U'; -- SUPORTE ACCOUNT (BASE64)
SET @CasTusDsc_SA = 'SUPPORT ACCOUNT';
SET @CasTusLnk_SA = '/Manager/Dashboard';
SET @CasTusGrp_SA = 'SUPPORT MANAGER';

select 'Start - Apply Settings...';

-- step one, create app. //
SELECT 'create app...';

INSERT INTO CasApp (
    CasAppCod,
    CasAppAudIns,
    CasAppAudUpd,
    CasAppAudDlt,
    CasAppAudUsr,
    CasAppDsc,
    CasAppObs,
    CasAppBlq,
    CasAppBlqDtt,
    CasAppTst,
    CasAppTstDtt,
    CasAppVer,
    CasAppVerDtt,
    CasAppVerLnk,
    CasAppKey,
    CasAppKeyExp,
    CasAppGrp
)
SELECT
    @CasAppCod,
    @now,
    NULL,
    NULL,
    @usr,
    @CasAppDsc,
    @CasAppObs,
    'N',
    NULL,
    'N',
    NULL,
    @CasAppVer,
    @now,
    @CasAppVerLnk,
    @CasAppKey,
    NULL,
    @CasAppGrp
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasApp
    WHERE CasAppCod = @CasAppCod
);

-- step two, create repository
SELECT 'create repository admin...';

INSERT INTO CasRps (
    CasRpsCod,
    CasRpsAudIns,
    CasRpsAudUpd,
    CasRpsAudDlt,
    CasRpsAudUsr,
    CasRpsDsc,
    CasRpsObs,
    CasRpsBlq,
    CasRpsBlqDtt,
    CasRpsGrp
)
SELECT 
    @CasRpsCod,
    @now,
    null,
    null,
    @usr,
    @CasRpsDsc,
    null,
    'N',
    null,
    @CasRpsCod
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRps
    WHERE CasRpsCod = @CasRpsCod
);

SELECT 'create repository support...';

INSERT INTO CasRps (
    CasRpsCod,
    CasRpsAudIns,
    CasRpsAudUpd,
    CasRpsAudDlt,
    CasRpsAudUsr,
    CasRpsDsc,
    CasRpsObs,
    CasRpsBlq,
    CasRpsBlqDtt,
    CasRpsGrp
)
SELECT 
    @CasRpsCod_SA,
    @now,
    null,
    null,
    @usr,
    @CasRpsDsc_SA,
    null,
    'N',
    null,
    @CasRpsCod_SA
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRps
    WHERE CasRpsCod = @CasRpsCod_SA
);

-- step tree, create repository and application
SELECT 'create repository and application...';

INSERT INTO CasRpa (
    CasRpsCod,
    CasAppCod,
    CasRpaAudIns,
    CasRpaAudUpd,
    CasRpaAudDlt,
    CasRpaAudUsr,
    CasRpaDsc,
    CasRpaObs,
    CasRpaBlq,
    CasRpaBlqDtt,
    CasRpaGrp
)
SELECT 
    @CasRpsCod,
    @CasAppCod,
    @now,
    null,
    null,
    @usr,
    @CasAppDsc,
    @CasAppObs,
    'N',
    null,
    @CasAppGrp
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRpa
    WHERE CasRpsCod = @CasRpsCod
      AND CasAppCod = @CasAppCod
);

INSERT INTO CasRpa (
    CasRpsCod,
    CasAppCod,
    CasRpaAudIns,
    CasRpaAudUpd,
    CasRpaAudDlt,
    CasRpaAudUsr,
    CasRpaDsc,
    CasRpaObs,
    CasRpaBlq,
    CasRpaBlqDtt,
    CasRpaGrp
)
SELECT 
    @CasRpsCod_SA,
    @CasAppCod,
    @now,
    null,
    null,
    @usr,
    @CasAppDsc,
    @CasAppObs,
    'N',
    null,
    @CasAppGrp
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRpa
    WHERE CasRpsCod = @CasRpsCod_SA
      AND CasAppCod = @CasAppCod
);

-- step four, create users
SELECT 'create user admin...';

INSERT INTO CasUsr (
    CasUsrCod,
    CasUsrAudIns,
    CasUsrAudUpd,
    CasUsrAudDlt,
    CasUsrAudUsr,
    CasUsrNme,
    CasUsrSnm,
    CasUsrNck,
    CasUsrDsc,
    CasUsrDmn,
    CasUsrLgn,
    CasUsrPwd,
    CasUsrBlq,
    CasUsrBlqDtt 
)
SELECT 
    @CasUsrCod,
    @now,
    null,
    null,
    @usr,
    @CasUsrNme,
    @CasUsrSnm,
    null,
    @CasUsrDsc,
    @CasUsrDmn,
    @CasUsrLgn,
    @CasUsrPwd,
    'N',
    null
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasUsr
    WHERE CasUsrCod = @CasUsrCod
);

SELECT 'create user support...';

INSERT INTO CasUsr (
    CasUsrCod,
    CasUsrAudIns,
    CasUsrAudUpd,
    CasUsrAudDlt,
    CasUsrAudUsr,
    CasUsrNme,
    CasUsrSnm,
    CasUsrNck,
    CasUsrDsc,
    CasUsrDmn,
    CasUsrLgn,
    CasUsrPwd,
    CasUsrBlq,
    CasUsrBlqDtt 
)
SELECT 
    @CasUsrCod_SA,
    @now,
    null,
    null,
    @usr,
    @CasUsrNme_SA,
    @CasUsrSnm_SA,
    null,
    @CasUsrDsc_SA,
    @CasUsrDmn_SA,
    @CasUsrLgn_SA,
    @CasUsrPwd_SA,
    'N',
    null
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasUsr
    WHERE CasUsrCod = @CasUsrCod_SA
);

-- step five, create type user
SELECT 'create type user admin...';

INSERT INTO CasTus (
    CasRpsCod,
    CasTusCod,
    CasTusAudIns,
    CasTusAudUpd,
    CasTusAudDlt,
    CasTusAudUsr,
    CasTusDsc,
    CasTusObs,
    CasTusBlq,
    CasTusBlqDtt,
    CasTusLnk,
    CasTusGrp
)
SELECT 
    @CasRpsCod,
    @CasTusCod,
    @now,
    null,
    null,
    @usr,
    @CasTusDsc,
    null,
    'N',
    null,
    @CasTusLnk,
    @CasTusGrp
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasTus
    WHERE CasRpsCod = @CasRpsCod
      AND CasTusCod = @CasTusCod
);

-- step five, create type user
SELECT 'create type user support...';

INSERT INTO CasTus (
    CasRpsCod,
    CasTusCod,
    CasTusAudIns,
    CasTusAudUpd,
    CasTusAudDlt,
    CasTusAudUsr,
    CasTusDsc,
    CasTusObs,
    CasTusBlq,
    CasTusBlqDtt,
    CasTusLnk,
    CasTusGrp
)
SELECT 
    @CasRpsCod_SA,
    @CasTusCod_SA,
    @now,
    null,
    null,
    @usr,
    @CasTusDsc_SA,
    null,
    'N',
    null,
    @CasTusLnk_SA,
    @CasTusGrp_SA
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasTus
    WHERE CasRpsCod = @CasRpsCod_SA
      AND CasTusCod = @CasTusCod_SA
);

-- step six, create user and repository
SELECT 'create user and repository...';

INSERT INTO CasRpu (
    CasRpsCod,
    CasUsrCod,
    CasRpuAudIns,
    CasRpuAudUpd,
    CasRpuAudDlt,
    CasRpuAudUsr,
    CasTusCod,
    CasRpuDsc,
    CasRpuBlq,
    CasRpuBlqDtt
)
SELECT 
    @CasRpsCod,
    @CasUsrCod,
    @now,
    null,
    null,
    @usr,
    @CasTusCod,
    @CasRpuDsc,
    'N',
    null
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRpu
    WHERE CasRpsCod = @CasRpsCod
      and CasUsrCod = @CasUsrCod
);

INSERT INTO CasRpu (
    CasRpsCod,
    CasUsrCod,
    CasRpuAudIns,
    CasRpuAudUpd,
    CasRpuAudDlt,
    CasRpuAudUsr,
    CasTusCod,
    CasRpuDsc,
    CasRpuBlq,
    CasRpuBlqDtt
)
SELECT 
    @CasRpsCod_SA,
    @CasUsrCod_SA,
    @now,
    null,
    null,
    @usr,
    @CasTusCod_SA,
    @CasRpuDsc_SA,
    'N',
    null
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM CasRpu
    WHERE CasRpsCod = @CasRpsCod_SA
      and CasUsrCod = @CasUsrCod_SA
);

select 'End - Apply Settings...';
