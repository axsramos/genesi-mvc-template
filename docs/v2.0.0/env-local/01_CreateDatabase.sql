create database sbadmin_local;

use sbadmin_local;

-- // Token // --
create table CasTkn (
    CasTknCod char(65) not null,
    CasTknAudIns datetime default now() not null,
    CasTknAudUpd datetime null,
    CasTknAudDlt datetime null,
    CasTknAudUsr char(255) null,
    CasTknDsc varchar(255) not null,
    CasTknBlq enum('S','N') default 'N' not null,
    CasTknBlqDtt datetime null,
    CasTknKey char(65) not null,
    CasTknKeyExp datetime null,
    constraint PKCasTkn primary key(CasTknCod),
    index IXCasTkn01 (CasTknAudIns asc),
    index IXCasTkn02 (CasTknBlq asc, CasTknDsc asc),
    index IXCasTkn03 (CasTknKey asc)
);

-- // Aplicativo // --
create table CasApp (
    CasAppCod char(65) not null,
    CasAppAudIns datetime default now() not null,
    CasAppAudUpd datetime null,
    CasAppAudDlt datetime null,
    CasAppAudUsr char(255) null,
    CasAppDsc varchar(255) not null,
    CasAppObs varchar(999) null,
    CasAppBlq enum('S','N') default 'N' not null,
    CasAppBlqDtt datetime null,
    CasAppTst enum('S','N') default 'S' not null,
    CasAppTstDtt datetime null,
    CasAppVer char(10) null,
    CasAppVerDtt datetime null,
    CasAppVerLnk varchar(999) null,
    CasAppKey char(65) not null,
    CasAppKeyExp datetime null,
    CasAppGrp char(65) not null,
    constraint PKCasApp primary key(CasAppCod),
    index IXCasApp01 (CasAppAudIns asc),
    index IXCasApp02 (CasAppGrp asc, CasAppDsc asc),
    index IXCasApp03 (CasAppBlq asc, CasAppDsc asc),
    index IXCasApp04 (CasAppKey asc)
);

-- // Repositório // --
create table CasRps (
    CasRpsCod char(65) not null,
    CasRpsAudIns datetime default now() not null,
    CasRpsAudUpd datetime null,
    CasRpsAudDlt datetime null,
    CasRpsAudUsr char(255) null,
    CasRpsDsc varchar(255) not null,
    CasRpsObs varchar(999) null,
    CasRpsBlq enum('S','N') default 'N' not null,
    CasRpsBlqDtt datetime null,
    CasRpsGrp char(65) not null,
    constraint PKCasRps primary key(CasRpsCod),
    index IXCasRps01 (CasRpsAudIns asc),
    index IXCasRps02 (CasRpsGrp asc, CasRpsDsc asc),
    index IXCasRps03 (CasRpsBlq asc, CasRpsDsc asc)
);

-- // Repositório & Aplicativo // --
create table CasRpa (
    CasRpsCod char(65) not null,
    CasAppCod char(65) not null,
    CasRpaAudIns datetime default now() not null,
    CasRpaAudUpd datetime null,
    CasRpaAudDlt datetime null,
    CasRpaAudUsr char(255) null,
    CasRpaDsc varchar(255) not null,
    CasRpaObs varchar(999) null,
    CasRpaBlq enum('S','N') default 'N' not null,
    CasRpaBlqDtt datetime null,
    CasRpaGrp char(65) not null,
    constraint PKCasRpa primary key(CasRpsCod, CasAppCod),
    constraint FKCasRpa01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    constraint FKCasRpa02 foreign key(CasAppCod) references CasApp(CasAppCod),
    index IXCasRpa01 (CasRpsCod asc),
    index IXCasRpa02 (CasAppCod asc),
    index IXCasRpa03 (CasRpsCod asc, CasRpaGrp asc, CasRpaDsc asc),
    index IXCasRpa04 (CasRpsCod asc, CasRpaBlq asc, CasRpaDsc asc)
);

-- // Usuário // --
create table CasUsr (
    CasUsrCod char(65) not null,
    CasUsrAudIns datetime default now() not null,
    CasUsrAudUpd datetime null,
    CasUsrAudDlt datetime null,
    CasUsrAudUsr char(255) null,
    CasUsrNme varchar(65) not null,
    CasUsrSnm varchar(65) null,
    CasUsrNck varchar(65) null,
    CasUsrDsc varchar(255) not null,
    CasUsrDmn varchar(65) not null,
    CasUsrLgn varchar(65) not null,
    CasUsrPwd varchar(255) null,
    CasUsrBlq enum('S','N') default 'N' not null,
    CasUsrBlqDtt datetime null,
    constraint PKCasUsr primary key(CasUsrCod),
    index IXCasUsr01 (CasUsrAudIns asc),
    index IXCasUsr02 (CasUsrDmn asc, CasUsrLgn asc),
    index IXCasUsr03 (CasUsrBlq asc, CasUsrDsc asc)
);

-- // Tipo de Usuário // --
create table CasTus (
    CasRpsCod char(65) not null,
    CasTusCod char(65) not null,
    CasTusAudIns datetime default now() not null,
    CasTusAudUpd datetime null,
    CasTusAudDlt datetime null,
    CasTusAudUsr char(255) null,
    CasTusDsc varchar(255) not null,
    CasTusObs varchar(999) null,
    CasTusBlq enum('S','N') default 'N' not null,
    CasTusBlqDtt datetime null,
    CasTusLnk varchar(999) null,
    CasTusGrp char(65) not null,
    constraint PKCasTus primary key(CasRpsCod, CasTusCod),
    constraint FKCasTus01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasTus01 (CasRpsCod asc),
    index IXCasTus02 (CasRpsCod asc, CasTusGrp asc, CasTusDsc asc),
    index IXCasTus03 (CasRpsCod asc, CasTusBlq asc, CasTusDsc asc)
);

-- // Repositório & Usuário // --
create table CasRpu (
    CasRpsCod char(65) not null,
    CasUsrCod char(65) not null,
    CasRpuAudIns datetime default now() not null,
    CasRpuAudUpd datetime null,
    CasRpuAudDlt datetime null,
    CasRpuAudUsr char(255) null,
    CasTusCod char(36) not null,
    CasRpuDsc varchar(255) not null,
    CasRpuBlq enum('S','N') default 'N' not null,
    CasRpuBlqDtt datetime null,
    constraint PKCasRpu primary key(CasRpsCod, CasUsrCod),
    constraint FKCasRpu01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    constraint FKCasRpu02 foreign key(CasUsrCod) references CasUsr(CasUsrCod),
    constraint FKCasRpu03 foreign key(CasRpsCod, CasTusCod) references CasTus(CasRpsCod, CasTusCod),
    index IXCasRpu01 (CasRpsCod asc),
    index IXCasRpu02 (CasUsrCod asc),
    index IXCasRpu03 (CasRpsCod asc, CasTusCod asc),
    index IXCasRpu04 (CasRpsCod asc, CasRpuBlq asc, CasRpuDsc asc)
);

-- // Funcionalidade // --
create table CasFun (
    CasRpsCod char(65) not null,
    CasFunCod char(65) not null,
    CasFunAudIns datetime default now() not null,
    CasFunAudUpd datetime null,
    CasFunAudDlt datetime null,
    CasFunAudUsr char(255) null,
    CasFunDsc varchar(255) not null,
    CasFunBlq enum('S','N') default 'N' not null,
    CasFunBlqDtt datetime null,
    constraint PKCasFun primary key(CasRpsCod, CasFunCod),
    constraint FKCasFun01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasFun01 (CasRpsCod asc),
    index IXCasFun05 (CasRpsCod asc, CasFunBlq asc, CasFunDsc asc)
);

-- // Módulo // --
create table CasMdl (
    CasRpsCod char(65) not null,
    CasMdlCod char(65) not null,
    CasMdlAudIns datetime default now() not null,
    CasMdlAudUpd datetime null,
    CasMdlAudDlt datetime null,
    CasMdlAudUsr char(255) null,
    CasMdlDsc varchar(255) not null,
    CasMdlBlq enum('S','N') default 'N' not null,
    CasMdlBlqDtt datetime null,
    constraint PKCasMdl primary key(CasRpsCod, CasMdlCod),
    constraint FKCasMdl01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasMdl01 (CasRpsCod asc),
    index IXCasMdl05 (CasRpsCod asc, CasMdlBlq asc, CasMdlDsc asc)
);

-- // Programa // --
create table CasPrg (
    CasRpsCod char(65) not null,
    CasPrgCod char(65) not null,
    CasPrgAudIns datetime default now() not null,
    CasPrgAudUpd datetime null,
    CasPrgAudDlt datetime null,
    CasPrgAudUsr char(255) null,
    CasPrgDsc varchar(255) not null,
    CasPrgBlq enum('S','N') default 'N' not null,
    CasPrgBlqDtt datetime null,
    CasPrgTst enum('S','N') default 'N' not null,
    CasPrgTstDtt datetime null,
    constraint PKCasPrg primary key(CasRpsCod, CasPrgCod),
    constraint FKCasPrg01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasPrg01 (CasRpsCod asc),
    index IXCasPrg05 (CasRpsCod asc, CasPrgBlq asc, CasPrgDsc asc)
);

-- // Parâmetro // --
create table CasPar (
    CasRpsCod char(65) not null,
    CasParCod char(65) not null,
    CasParTbl char(65) not null,
    CasParAudIns datetime default now() not null,
    CasParAudUpd datetime null,
    CasParAudDlt datetime null,
    CasParAudUsr char(255) null,
    CasParDsc varchar(255) not null,
    CasParBlq enum('S','N') default 'N' not null,
    CasParBlqDtt datetime null,
    CasParSeq int null,
    CasParTxt varchar(4000) null,
    CasParGrp char(65) not null,
    constraint PKCasPar primary key(CasRpsCod, CasParCod),
    constraint FKCasPar01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasPar01 (CasRpsCod asc),
    index IXCasPar02 (CasRpsCod asc, CasParTbl asc),
    index IXCasPar03 (CasRpsCod asc, CasParGrp asc, CasParDsc asc),
    index IXCasPar04 (CasRpsCod asc, CasParBlq asc, CasParDsc asc)
);

-- // Funcionalidade & Programa // --
create table CasFpr (
    CasRpsCod char(65) not null,
    CasFunCod char(65) not null,
    CasPrgCod char(65) not null,
    CasFprAudIns datetime default now() not null,
    CasFprAudUpd datetime null,
    CasFprAudDlt datetime null,
    CasFprAudUsr char(255) null,
    constraint PKCasFpr primary key(CasRpsCod, CasFunCod, CasPrgCod),
    constraint FKCasFpr01 foreign key(CasRpsCod, CasFunCod) references CasFun(CasRpsCod, CasFunCod),
    constraint FKCasFpr02 foreign key(CasRpsCod, CasPrgCod) references CasPrg(CasRpsCod, CasPrgCod),
    index IXCasFpr01 (CasRpsCod asc, CasFunCod asc),
    index IXCasFpr02 (CasRpsCod asc, CasPrgCod asc)
);

-- // Módulo & Programa // --
create table CasMpr (
    CasRpsCod char(65) not null,
    CasMdlCod char(65) not null,
    CasPrgCod char(65) not null,
    CasMprAudIns datetime default now() not null,
    CasMprAudUpd datetime null,
    CasMprAudDlt datetime null,
    CasMprAudUsr char(255) null,
    constraint PKCasMpr primary key(CasRpsCod, CasMdlCod, CasPrgCod),
    constraint FKCasMpr01 foreign key(CasRpsCod, CasMdlCod) references CasMdl(CasRpsCod, CasMdlCod),
    constraint FKCasMpr02 foreign key(CasRpsCod, CasPrgCod) references CasPrg(CasRpsCod, CasPrgCod),
    index IXCasMpr01 (CasRpsCod asc, CasMdlCod asc),
    index IXCasMpr02 (CasRpsCod asc, CasPrgCod asc)
);

-- // Menu Raiz // --
create table CasMnu (
    CasRpsCod char(65) not null,
    CasMnuCod char(65) not null,
    CasMnuAudIns datetime default now() not null,
    CasMnuAudUpd datetime null,
    CasMnuAudDlt datetime null,
    CasMnuAudUsr char(255) null,
    CasMnuDsc varchar(255) not null,
    CasMnuBlq enum('S','N') default 'N' not null,
    CasMnuBlqDtt datetime null,
    CasMnuTxt varchar(4000) null,
    CasMnuGrp char(65) not null,
    constraint PKCasMnu primary key(CasRpsCod, CasMnuCod),
    constraint FKCasMnu01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasMnu01 (CasRpsCod asc),
    index IXCasMnu02 (CasRpsCod asc, CasMnuGrp asc, CasMnuDsc asc),
    index IXCasMnu03 (CasRpsCod asc, CasMnuBlq asc, CasMnuDsc asc)
);

-- // Menu Árvore // --
create table CasMna (
    CasRpsCod char(65) not null,
    CasMnuCod char(65) not null,
    CasMnaCod char(65) not null,
    CasMnaAudIns datetime default now() not null,
    CasMnaAudUpd datetime null,
    CasMnaAudDlt datetime null,
    CasMnaAudUsr char(255) null,
    CasMnaDsc varchar(255) not null,
    CasMnaBlq enum('S','N') default 'N' not null,
    CasMnaBlqDtt datetime null,
    CasPrgCod char(65) null,
    CasMnaLnk varchar(999) null,
    CasMnaIco varchar(999) null,
    CasMnaTxt varchar(4000) null,
    CasMnaGrp char(65) not null,
    constraint PKCasMna primary key(CasRpsCod, CasMnuCod, CasMnaCod),
    constraint FKCasMna01 foreign key(CasRpsCod, CasMnuCod) references CasMnu(CasRpsCod, CasMnuCod),
    constraint FKCasMna02 foreign key(CasRpsCod, CasPrgCod) references CasPrg(CasRpsCod, CasPrgCod),
    index IXCasMna01 (CasRpsCod asc, CasMnuCod asc),
    index IXCasMna02 (CasRpsCod asc, CasMnuCod asc, CasMnaGrp asc, CasMnaDsc asc),
    index IXCasMna03 (CasRpsCod asc, CasMnuCod asc, CasMnaBlq asc, CasMnaDsc asc),
    index IXCasMna04 (CasRpsCod asc, CasPrgCod asc)
);

-- // Perfil // --
create table CasPfi (
    CasRpsCod char(65) not null,
    CasPfiCod char(65) not null,
    CasPfiAudIns datetime default now() not null,
    CasPfiAudUpd datetime null,
    CasPfiAudDlt datetime null,
    CasPfiAudUsr char(255) null,
    CasPfiDsc varchar(255) not null,
    CasPfiBlq enum('S','N') default 'N' not null,
    CasPfiBlqDtt datetime null,
    constraint PKCasPfi primary key(CasRpsCod, CasPfiCod),
    constraint FKCasPfi01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasPfi01 (CasRpsCod asc),
    index IXCasPfi02 (CasRpsCod asc, CasPfiBlq asc, CasPfiDsc asc)
);

-- // Perfil & Usuário // --
create table CasPfu (
    CasRpsCod char(65) not null,
    CasPfiCod char(65) not null,
    CasUsrCod char(65) not null,
    CasPfuAudIns datetime default now() not null,
    CasPfuAudUpd datetime null,
    CasPfuAudDlt datetime null,
    CasPfuAudUsr char(255) null,
    constraint PKCasPfu primary key(CasRpsCod, CasPfiCod, CasUsrCod),
    constraint FKCasPfu01 foreign key(CasRpsCod, CasPfiCod) references CasPfi(CasRpsCod, CasPfiCod),
    constraint FKCasPfu02 foreign key(CasRpsCod, CasUsrCod) references CasRpu(CasRpsCod, CasUsrCod),
    index IXCasPfu01 (CasRpsCod asc, CasPfiCod asc),
    index IXCasPfu02 (CasRpsCod asc, CasUsrCod asc)
);

-- // Autorização do Perfil // --
create table CasApf (
    CasRpsCod char(65) not null,
    CasPfiCod char(65) not null,
    CasUsrCod char(65) not null,
    CasPrgCod char(65) not null,
    CasApfAudIns datetime default now() not null,
    CasApfAudUpd datetime null,
    CasApfAudDlt datetime null,
    CasApfAudUsr char(255) null,
    constraint PKCasApf primary key(CasRpsCod, CasPfiCod, CasUsrCod, CasPrgCod),
    constraint FKCasApf01 foreign key(CasRpsCod, CasPfiCod, CasUsrCod) references CasPfu(CasRpsCod, CasPfiCod, CasUsrCod),
    constraint FKCasApf02 foreign key(CasRpsCod, CasPrgCod) references CasPrg(CasRpsCod, CasPrgCod),
    index IXCasApf01 (CasRpsCod asc, CasPfiCod asc, CasUsrCod asc),
    index IXCasApf02 (CasRpsCod asc, CasPrgCod asc)
);

-- // Autorização da Funcionalidade // --
create table CasAfu (
    CasRpsCod char(65) not null,
    CasPfiCod char(65) not null,
    CasUsrCod char(65) not null,
    CasPrgCod char(65) not null,
    CasFunCod char(65) not null,
    CasAfuAudIns datetime default now() not null,
    CasAfuAudUpd datetime null,
    CasAfuAudDlt datetime null,
    CasAfuAudUsr char(255) null,
    constraint PKCasAfu primary key(CasRpsCod, CasPfiCod, CasUsrCod, CasPrgCod, CasFunCod),
    constraint FKCasAfu01 foreign key(CasRpsCod, CasPfiCod, CasUsrCod, CasPrgCod) references CasApf(CasRpsCod, CasPfiCod, CasUsrCod, CasPrgCod),
    constraint FKCasAfu02 foreign key(CasRpsCod, CasFunCod, CasPrgCod) references CasFpr(CasRpsCod, CasFunCod, CasPrgCod),
    index IXCasAfu01 (CasRpsCod asc, CasPfiCod asc, CasUsrCod asc, CasPrgCod asc),
    index IXCasAfu02 (CasRpsCod asc, CasFunCod asc, CasPrgCod asc)
);

-- // WorkStation // --
create table CasWks (
    CasRpsCod char(65) not null,
    CasWksCod char(65) not null,
    CasWksAudIns datetime default now() not null,
    CasWksAudUpd datetime null,
    CasWksAudDlt datetime null,
    CasWksAudUsr char(255) null,
    CasWksDsc varchar(255) not null,
    CasWksObs varchar(999) null,
    CasWksBlq enum('S','N') default 'N' not null,
    CasWksBlqDtt datetime null,
    CasWksMac character(17) default '00:00:00:00:00:00' not null,
	CasWksEip character(45) default '0.0.0.0' not null,
	CasWksChv character(36) not null,
    CasWksGrp char(65) not null,
    constraint PKCasWks primary key(CasRpsCod, CasWksCod),
    index IXCasWks01 (CasRpsCod asc, CasWksGrp asc, CasWksDsc asc),
    index IXCasWks02 (CasRpsCod asc, CasWksBlq asc, CasWksDsc asc),
    index IXCasWks03 (CasRpsCod asc, CasWksChv asc)
);

-- // Sessão Web // --
create table CasSwb (
    CasRpsCod char(65) not null,
    CasSwbCod char(65) not null,
    CasSwbAudIns datetime default now() not null,
    CasSwbAudUpd datetime null,
    CasSwbAudDlt datetime null,
    CasSwbAudUsr char(255) null,
    CasSwbBlq enum('S','N') default 'N' not null,
    CasSwbBlqDtt datetime null,
    CasSwbWks varchar(65) null,
	CasSwbUsu varchar(65) null,
	CasSwbBrw varchar(65) null,
	CasSwbIni datetime null,
	CasSwbFin datetime null,
	CasSwbUsrCod char(36) null,
	CasSwbWksCod char(36) null,
    constraint PKCasSwb primary key(CasRpsCod, CasSwbCod),
    constraint FKCasSwb01 foreign key(CasRpsCod) references CasRps(CasRpsCod),
    index IXCasSwb01 (CasRpsCod asc),
    index IXCasSwb02 (CasRpsCod asc, CasSwbAudIns asc),
    index IXCasSwb03 (CasRpsCod asc, CasSwbUsrCod asc),
    index IXCasSwb04 (CasRpsCod asc, CasSwbWksCod asc)
);

-- // Histórico de Navegação  // --
create table CasSwn (
    CasRpsCod char(65) not null,
    CasSwbCod char(65) not null,
    CasSwnCod int not null,
    CasSwnDsc varchar(255) null,
    CasSwnAudIns datetime default now() not null,
    CasSwnAudUpd datetime null,
    CasSwnAudDlt datetime null,
    CasSwnAudUsr char(255) null,
    constraint PKCasSwn primary key(CasRpsCod, CasSwbCod, CasSwnCod),
    constraint FKCasSwn01 foreign key(CasRpsCod, CasSwbCod) references CasSwb(CasRpsCod, CasSwbCod),
    index IXCasSwn01 (CasRpsCod asc, CasSwbCod asc),
    index IXCasSwn02 (CasRpsCod asc, CasSwbCod asc, CasSwnCod asc)
);

-- // Sessão do Programa // --
create table CasSwp (
    CasRpsCod char(65) not null,
    CasSwbCod char(65) not null,
    CasPrgCod char(65) not null,
    CasSwpAudIns datetime default now() not null,
    CasSwpAudUpd datetime null,
    CasSwpAudDlt datetime null,
    CasSwpAudUsr char(255) null,
    CasSwpTxt varchar(4000) null,
    constraint PKCasSwp primary key(CasRpsCod, CasSwbCod, CasPrgCod),
    constraint FKCasSwp01 foreign key(CasRpsCod, CasSwbCod) references CasSwb(CasRpsCod, CasSwbCod),
    constraint FKCasSwp02 foreign key(CasRpsCod, CasPrgCod) references CasPrg(CasRpsCod, CasPrgCod),
    index IXCasSwp01 (CasRpsCod asc, CasSwbCod asc),
    index IXCasSwp02 (CasRpsCod asc, CasPrgCod asc)
);
